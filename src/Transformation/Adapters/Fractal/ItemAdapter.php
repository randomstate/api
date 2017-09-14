<?php


namespace RandomState\Api\Transformation\Adapters\Fractal;


use ArrayAccess;
use League\Fractal\Resource\Item;
use RandomState\Api\Transformation\Adapters\FractalAdapter;

class ItemAdapter extends FractalAdapter {

	public function transforms($data)
	{
		return is_object($data) && !(is_array($data) || $data instanceof ArrayAccess) && $this->switchboard->transforms($data);
	}

	public function getResource($data)
	{
		return new Item($data, $this->switchboard);
	}
}