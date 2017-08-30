<?php


namespace CImrie\Api\Namespaces;

use CImrie\Api\Versioning\Manager as VersionManager;

class CustomNamespace {

	/**
	 * @var VersionManager
	 */
	protected $versions;

	/**
	 * @param VersionManager $versions
	 */
	public function __construct(VersionManager $versions)
	{
		$this->versions = $versions;
	}

	public function versions()
	{
		return $this->versions;
	}
}