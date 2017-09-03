<?php


namespace RandomState\Api\Transformation\Adapters;


interface Adapter {

	public function transforms($data);

	public function run($data);
}