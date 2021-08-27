<?php


namespace RandomState\Tests\Api\Feature\Transformation\Fractal;


use RandomState\Api\Transformation\Adapters\Fractal\ItemAdapter;
use RandomState\Api\Transformation\Adapters\Fractal\NullAdapter;
use RandomState\Api\Transformation\Adapters\Fractal\ScalarAdapter;
use RandomState\Api\Transformation\Fractal\Resolver;
use RandomState\Api\Transformation\Fractal\Switchboard;
use RandomState\Api\Transformation\Manager;
use RandomState\Tests\Api\TestCase;

class CanTransformItemsTest extends TestCase {

	/**
	 * @var Manager
	 */
	protected $manager;

	/**
	 * @var \League\Fractal\Manager
	 */
	protected $fractal;

	/**
	 * @var Switchboard
	 */
	protected $switchboard;

	/**
	 * @var Resolver
	 */
	protected $resolver;

	protected function setUp() : void
	{
		parent::setUp();

		$this->manager = new Manager();
		$this->fractal = new \League\Fractal\Manager();
		$this->switchboard = new Switchboard($this->resolver = new Resolver());
	}

	/**
	 * @test
	 */
	public function can_transform_object_into_single_item_for_output()
	{
		$this->manager->register(
			new ItemAdapter($this->fractal, $this->switchboard)
		);

		$this->resolver->bind(Prize::class, PrizeTransformer::class);

		$output = $this->manager->transform(new Prize());

		$this->assertEquals(
			[
				'data' => [
					'value' => 100,
				]
			],
			$output
		);
	}

	/**
	 * @test
	 */
	public function can_transform_scalar_values_such_as_primitives()
	{
	    $this->manager->register(
	    	new ScalarAdapter($this->fractal)
	    );

		$this->assertEquals(
			[
				'data' => 'hello'
			],
			$this->manager->transform('hello')
		);

		$this->assertEquals(
			[
				'data' => 1239
			],
			$this->manager->transform(1239)
		);

		$this->assertEquals(
			[
				'data' => false
			],
			$this->manager->transform(false)
		);

		$this->assertEquals(
			[
				'data' => null
			],
			$this->manager->transform(null)
		);
	}
}