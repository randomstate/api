<?php


namespace CImrie\Api\Transformation\Adapters;


interface Adapter {

	public function transforms($data);

	public function run($data);
}