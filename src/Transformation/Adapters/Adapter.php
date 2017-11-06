<?php


namespace RandomState\Api\Transformation\Adapters;


interface Adapter {

	public function transforms($data);

	public function include(array $includes);

	public function exclude(array $excludes);

	public function run($data);
}