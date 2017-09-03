<?php


namespace RandomState\Api\Namespaces;


use RandomState\Api\Transformation\Manager as TransformManager;
use Closure;

class Manager {

	/**
	 * @var array
	 */
	protected $namespaces;

	public function __construct(CustomNamespace $default = null)
	{
		$this->register('default', $default);
	}

	public function register($name, $resolver)
	{
		$this->namespaces[$name] = $resolver;
	}

	/**
	 * @param string|null $name
	 *
	 * @return CustomNamespace
	 */
	public function getNamespace($name = null)
	{
		if(!$name) {
			return $this->resolve('default');
		}

		return $this->resolve($name);
	}

	protected function resolve($name) {
		$resolver = isset($this->namespaces[$name]) ? $this->namespaces[$name] : null;

		if($resolver instanceof Closure) {
			$this->namespaces[$name] = $resolver();
		}

		return $this->namespaces[$name];
	}
}