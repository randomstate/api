<?php


namespace RandomState\Api\Transformation\Adapters\Fractal;


use League\Fractal\Manager;
use League\Fractal\Resource\Primitive;
use RandomState\Api\Transformation\Adapters\Adapter;
use RandomState\Api\Transformation\Fractal\ScalarTransformer;

class ScalarAdapter implements Adapter {

	/**
	 * @var Manager
	 */
	protected $manager;

	public function __construct(Manager $manager)
	{
		$this->manager = $manager;
	}

	public function transforms($data)
	{
		return is_scalar($data) || $data === null;
	}

	public function getResource($data)
	{
		return new Primitive($data, new ScalarTransformer);
	}

	public function run($data)
	{
		return [
			'data' => $this->manager->createData($this->getResource($data))->transformPrimitiveResource()
		];
	}
}