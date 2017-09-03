<?php


namespace RandomState\Api\Transformation\Fractal;


use League\Fractal\TransformerAbstract;

class Switchboard extends TransformerAbstract {

	/**
	 * @var Resolver
	 */
	protected $resolver;

	/**
	 * @var TransformerAbstract
	 */
	protected $currentTransformer;

	public function __construct(Resolver $resolver)
	{
		$this->resolver = $resolver;
	}

	public function transform($response)
	{
		$transformer = $this->resolver->get($response);
		$this->currentTransformer = $transformer;

		if(!$transformer) {
			return $response;
		}

		$this->currentTransformer = $transformer;
		return $transformer->transform($response);
	}

	public function getAvailableIncludes()
	{
		if(! $this->currentTransformer) {
			return [];
		}

		return $this->currentTransformer->getAvailableIncludes();
	}

	public function getDefaultIncludes()
	{
		if(! $this->currentTransformer) {
			return [];
		}

		return $this->currentTransformer->getDefaultIncludes();
	}

	public function __call($name, $arguments)
	{
		return call_user_func([$this->currentTransformer, $name], ...$arguments);
	}
}