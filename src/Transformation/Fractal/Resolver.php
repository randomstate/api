<?php


namespace RandomState\Api\Transformation\Fractal;


use Closure;

class Resolver {

	protected $binds = [];

	protected $factory;

	public function __construct(Closure $factory = null)
	{
		$this->factory = $factory;

		if(!$this->factory) {
			$this->factory = function($transformerClass) {
				return new $transformerClass;
			};
		}
	}

	/**
	 * @param string|Closure $class
	 * @param string|Closure $transformer
	 *
	 * @return $this
	 */
	public function bind($class, $transformer = null)
	{
		if(!$transformer) {
			return $this->autoBind($class);
		}

		$this->binds[$class] = $transformer;

		return $this;
	}

	public function autoBind($transformer)
	{
		$transformer = new \ReflectionClass($transformer);

		if($transformer->hasMethod('transform')) {
			$method = $transformer->getMethod('transform');
			$parameters = $method->getParameters();

			if(count($parameters) === 1) {
				$typeHint = $parameters[0]->getClass()->getName();

				$this->binds[$typeHint] = $transformer->name;

				return $this;
			}
		}

		throw new AutoBindingFailedException("Either the transform method does not exist on {$transformer->name} or it has no single type-hinted parameter.");
	}

	public function get($class)
	{
		if(is_object($class)) {
			$class = get_class($class);
		}

		if(is_string($class) && class_exists($class)) {
			return $this->resolve($class);
		}

		return $this->binds[$class] ?? null;
	}

	protected function resolve($class)
	{
		$transformer = $this->binds[$class] ?? null;

		if(!$transformer) {
			return null;
		}

		if($transformer instanceof Closure) {
			return $this->binds[$class] = $transformer();
		}

		return $this->binds[$class] = ($this->factory)($transformer);
	}
}