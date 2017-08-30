<?php


namespace CImrie\Api\Tests\Feature\Versioning;


use CImrie\Api\Namespaces\CustomNamespace;
use CImrie\Api\Namespaces\Manager as NamespaceManager;
use CImrie\Api\Versioning\Manager as VersionManager;
use CImrie\Api\Tests\Model\Transformation\User;
use CImrie\Api\Tests\TestCase;

use CImrie\Api\Transformation\Manager as TransformManager;
use CImrie\Api\Versioning\Version;
use Mockery as m;

class VersioningTest extends TestCase {

	/**
	 * @var NamespaceManager
	 */
	protected $manager;

	protected function setUp()
	{
		parent::setUp();
		$this->manager = new NamespaceManager(
			new CustomNamespace(
				new VersionManager()
			)
		);
	}

	/**
	 * @test
	 */
	public function can_create_new_versions_in_a_namespace()
	{
		$versionManager = $this->manager->getNamespace()
		                                ->versions();

		$transformManager = m::mock(TransformManager::class);
		$versionManager->register('1.0', function() use ($transformManager) {
			return $transformManager;
		});

		$version = $versionManager->get('1.0');

		$this->assertInstanceOf(Version::class, $version);
		$this->assertEquals($transformManager, $version->getTransformManager());
	}

	/**
	 * @test
	 */
	public function can_fallback_to_previous_versions_if_data_unmodified()
	{
		$versionManager      = $this->manager->getNamespace()
		                                     ->versions();
		$transformManager    = m::mock(TransformManager::class);
		$newTransformManager = m::mock(TransformManager::class);

		$versionManager->register('1.0', function() use ($transformManager) {
			return $transformManager;
		});
		$versionManager->register('1.1', function() use ($newTransformManager) {
			return $newTransformManager;
		})
		               ->inherit('1.0');

		$user = new User;

		$newTransformManager->shouldReceive('transform')->with($user)->once()->andReturn($user);
		$transformManager->shouldReceive('transform')->with($user)->andReturn($output = []);

		$user = $versionManager->get('1.1')->transform($user);

		$this->assertEquals($output, $user);
	}
}