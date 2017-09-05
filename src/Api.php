<?php

namespace RandomState\Api;

use RandomState\Api\Versioning\Manager;

interface Api {

	/**
	 * @return Manager
	 */
	public function versions();
}