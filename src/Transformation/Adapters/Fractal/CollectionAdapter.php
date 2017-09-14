<?php


namespace RandomState\Api\Transformation\Adapters\Fractal;


use ArrayAccess;
use League\Fractal\Resource\Collection;
use RandomState\Api\Transformation\Adapters\FractalAdapter;

class CollectionAdapter extends FractalAdapter {

	public function transforms($data)
	{
	    return is_array($data) || $data instanceof ArrayAccess;
	}

	function getResource($data)
	{
		return new Collection($data, $this->switchboard);
	}
}