<?php


namespace RandomState\Api\Transformation\Adapters\Fractal;


use ArrayAccess;
use League\Fractal\Resource\Collection;
use RandomState\Api\Transformation\Adapters\FractalAdapter;

class CollectionAdapter extends FractalAdapter {

	public function transforms($data)
	{
	    $isArrayable = (is_array($data) || $data instanceof ArrayAccess);
	    $canTransformEach = true;

	    if($isArrayable) {
	        foreach($data as $datum) {
	            $canTransformEach = $canTransformEach && $this->switchboard->transforms($datum);
            }
        }

        return $isArrayable && $canTransformEach;
	}

	function getResource($data)
	{
		return new Collection($data, $this->switchboard);
	}
}