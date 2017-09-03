<?php


namespace RandomState\Tests\Api\Feature\Transformation\Fractal;


use RandomState\Api\Transformation\Fractal\Resolver;
use RandomState\Tests\Api\TestCase;
use League\Fractal\TransformerAbstract;

class AutoTransformerResolutionTest extends TestCase {

	/**
	 * @var Resolver
	 */
	protected $resolver;

	protected function setUp()
	{
		parent::setUp();
		$this->resolver = new Resolver;
	}

	/**
	 * @test
	 */
	public function bound_transformer_is_resolved()
	{
		$this->resolver->bind(ExampleEntity::class, ExampleTransformer::class);
		$transformer = $this->resolver->get(ExampleEntity::class);

		$this->assertInstanceOf(ExampleTransformer::class, $transformer);
	}

	/**
	 * @test
	 */
	public function type_hinted_transformers_can_be_auto_resolved()
	{
	    $this->resolver->autoBind(ExampleTransformer::class);
		$null = $this->resolver->get(ExampleEntity::class);
		$transformer = $this->resolver->get(Auto::class);

		$this->assertNull($null);
		$this->assertInstanceOf(ExampleTransformer::class, $transformer);
	}

	/**
	 * @test
	 */
	public function can_mix_explicit_transformers_with_auto_resolved_ones()
	{
		$this->resolver->bind(ExampleTransformer::class);
		$this->resolver->bind(ExampleEntity::class, ExampleTransformer::class);

		$this->assertInstanceOf(ExampleTransformer::class, $this->resolver->get(ExampleEntity::class));
		$this->assertInstanceOf(ExampleTransformer::class, $this->resolver->get(Auto::class));
	}
}

class ExampleEntity {
	public $name = 'test';
}

class Auto extends ExampleEntity {

}

class ExampleTransformer extends TransformerAbstract {

	public function transform(Auto $entity)
	{
		return $entity->name;
	}
}