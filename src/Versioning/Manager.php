<?php


namespace RandomState\Api\Versioning;


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

	/**
	 * @return Version | null
	 */
	public function current()
	{
		$size = count($this->versions);
		$last = array_values($this->versions)[$size-1] ?? null;

		return $last;
	}
}