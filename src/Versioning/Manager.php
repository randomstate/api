<?php


namespace CImrie\Api\Versioning;


use Closure;

class Manager {

	/**
	 * @var Version[]
	 */
	protected $versions;

	public function __construct(array $versions = [])
	{
		$this->versions = $versions;
	}

	/**
	 * @param $identifier
	 * @param Closure $resolver
	 *
	 * @return Version $version
	 */
	public function register($identifier, Closure $resolver)
	{
		$this->versions[$identifier] = $version = new Version($this, $resolver);

		return $version;
	}

	/**
	 * @param $identifier
	 *
	 * @return Version $version
	 */
	public function get($identifier)
	{
		return $this->versions[$identifier];
	}
}