<?php


namespace RandomState\Tests\Api\Feature\Transformation\Fractal;


use RandomState\Api\Transformation\Fractal\Resolver;
use RandomState\Tests\Api\Model\Transformation\User;
use RandomState\Tests\Api\Model\Transformation\UserTransformer;
use RandomState\Tests\Api\TestCase;

class CanInjectDependenciesIntoTransformersTest extends TestCase {

	/**
	 * @test
	 */
	public function can_resolve_transformer_via_standard_factory()
	{
		$transformer = new UserTransformer();

	    $resolver = new Resolver(function() use($transformer) {
	    	return $transformer;
	    });

	    $resolver->bind(User::class, UserTransformer::class);
	    $this->assertEquals($transformer, $resolver->get(User::class));
	}
	
	/**
	 * @test
	 */
	public function can_resolve_transformer_via_a_closure()
	{
		$transformer = new UserTransformer();
		$resolver = new Resolver;

		$resolver->bind(User::class, function() use($transformer) {
			return $transformer;
		});

		$this->assertEquals($transformer, $resolver->get(User::class));
	}
}