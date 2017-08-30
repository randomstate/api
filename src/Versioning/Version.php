<?php


namespace CImrie\Api\Versioning;


use CImrie\Api\Transformation\Manager as TransformManager;
use Closure;

class Version {

	/**
	 * @var Manager
	 */
	protected $manager;
	/**
	 * @var Closure | TransformManager
	 */
	protected $transformResolver;

	/**
	 * @var Version
	 */
	protected $fallback;

	public function __construct(Manager $manager, Closure $transformResolver)
	{
		$this->manager = $manager;
		$this->transformResolver = $transformResolver;
	}

	public function inherit($versionIdentifier)
	{
		$this->fallback = $this->manager->get($versionIdentifier);

		return $this;
	}

	/**
	 * @param mixed $data
	 *
	 * @return mixed
	 */
	public function transform($data)
	{
		$output = $this->getTransformManager()->transform($data);

		if($output === $data && $this->fallback) {
			return $this->fallback->transform($data);
		}

		return $output;
	}

	/**
	 * @return TransformManager
	 */
	public function getTransformManager()
	{
		if($this->transformResolver instanceof Closure) {
			$this->transformResolver = ($this->transformResolver)();
		}

		return $this->transformResolver;
	}
}