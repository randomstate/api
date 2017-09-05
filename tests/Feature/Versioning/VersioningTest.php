<?php


namespace RandomState\Tests\Api\Feature\Versioning;


use RandomState\Api\Namespaces\CustomNamespace;
use RandomState\Api\Namespaces\Manager as NamespaceManager;
use RandomState\Api\Versioning\Manager as VersionManager;
use RandomState\Tests\Api\Model\Transformation\User;
use RandomState\Tests\Api\TestCase;

use RandomState\Api\Transformation\Manager as TransformManager;
use RandomState\Api\Versioning\Version;
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

	/**
	 * @test
	 */
	public function can_get_last_registered_version_with_helper_method()
	{
		$versions      = $this->manager->getNamespace()
		                                     ->versions();
		$transformManager    = m::mock(TransformManager::class);
		$newTransformManager = m::mock(TransformManager::class);

		$versions->register('1.0', function() use ($transformManager) {
			return $transformManager;
		});
		$versions->register('1.1', function() use ($newTransformManager) {
			return $newTransformManager;
		})
		               ->inherit('1.0');

		$this->assertEquals($newTransformManager, $versions->current()->getTransformManager());
	}

	/**
	 * @test
	 */
	public function can_reverse_lookup_version_identifier()
	{
		$versions      = $this->manager->getNamespace()
		                               ->versions();
		$transformManager    = m::mock(TransformManager::class);
		$newTransformManager = m::mock(TransformManager::class);

		$versions->register('1.0', function() use ($transformManager) {
			return $transformManager;
		});
		$versions->register('1.1', function() use ($newTransformManager) {
			return $newTransformManager;
		})
		         ->inherit('1.0');

		$this->assertEquals('1.0', $versions->identify($versions->get('1.0')));
	}
}