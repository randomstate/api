<?php


namespace RandomState\Api\Transformation\Fractal;


use League\Fractal\TransformerAbstract;

class ScalarTransformer extends TransformerAbstract {

	public function transform($data)
	{
		return $data;
	}
}