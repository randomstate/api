<?php


namespace CImrie\Api\Transformation;


use CImrie\Api\Transformation\Adapters\Adapter;

class Manager {

	/**
	 * @var Adapter[]
	 */
	protected $adapters;

	public function __construct(array $adapters = [])
	{
		$this->adapters = $adapters;
	}

	public function transform($data)
	{
		foreach($this->adapters as $adapter) {
			if($adapter->transforms($data)) {
				return $adapter->run($data);
			}
		}

		return $data;
	}

	public function register(Adapter $adapter)
	{
		$this->adapters = array_unique($this->adapters + [$adapter]);

		return $this;
	}
}