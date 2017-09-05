<?php


namespace RandomState\Tests\Api\Unit\Transformation\Fractal;


use League\Fractal\TransformerAbstract;
use RandomState\Api\Transformation\Fractal\Resolver;
use RandomState\Tests\Api\Model\Transformation\User;
use RandomState\Tests\Api\Model\Transformation\UserTransformer;
use RandomState\Tests\Api\TestCase;

class ResolverTest extends TestCase {

	/**
	 * @test
	 */
	public function uses_existing_transformer_if_already_resolved()
	{
		$transformer = new UserTransformer();

	    $resolver = new Resolver();

	    $resolver->bind(User::class, function() use($transformer){
	    	return $transformer;
	    });
//	    $resolver->bind(TestExistingTransformer::class);

	    $this->assertEquals($resolver->get(User::class), $resolver->get(User::class));
	}

	/**
	 * @test
	 */
	public function returns_null_if_no_transformer_bound()
	{
		$resolver = new Resolver(function($transformer) {
			return new $transformer;
		});

		$this->assertNull($resolver->get(User::class));
	}
}

class TestExisting {
	public $existing = 'yes';
}

class TestExistingTransformer extends TransformerAbstract {

	public function transform(TestExisting $existing)
	{
		return [
			'existing' => $existing->existing
		];
	}
}