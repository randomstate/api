<?php


namespace CImrie\Api\Tests\Feature\Transformation;


use CImrie\Api\Tests\Model\Transformation\User;
use CImrie\Api\Tests\TestCase;
use CImrie\Api\Transformation\Adapters\Adapter;
use CImrie\Api\Transformation\Manager;

use Mockery as m;

class TransformResponseTest extends TestCase {

	/**
	 * @var Manager
	 */
	protected $transformer;

	protected function setUp()
	{
		parent::setUp();

		$this->transformer = new Manager();
	}
	
	/**
	 * @test
	 */
	public function can_register_adapters_to_intercept_and_transform_data()
	{
		$this->transformer->register($adapter = m::mock(Adapter::class));
		$adapter->shouldReceive('transforms')->andReturn(true);
		$adapter->shouldReceive('run')->once()->andReturn(true);

		$data = $this->transformer->transform(new User);
		$this->assertTrue($data);
	}

	/**
	 * @test
	 */
	public function does_not_run_if_adapter_is_incompatible()
	{
		$this->transformer->register($adapter = m::mock(Adapter::class));
		$adapter->shouldReceive('transforms')->andReturn(false);

		$data = $this->transformer->transform($user = new User);
		$this->assertEquals($user, $data);
	}
}