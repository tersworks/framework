<?php

namespace Tersworks\Foundation;

use Tersworks\Foundation\Http\Request;

final class Router
{	
	private static ?Router $instance = null;

	/**
	 * @var array The list of routes.
	 */
	private array $routes = [];

	private function __construct() {}
	private function __clone() {}

	/**
	 * Get Router instance
	 * 
	 */
	public static function getInstance(): self
	{
		return self::$instance ?? self::$instance = new self();
	}

	/**
	 * Register a new GET route
	 * 
	 * @param  string   $uri      
	 * @param  callable $callback 
	 */
	
	public function get(string $uri, array|callable $callback): void 
	{
		$this->registerRoute(['GET', 'HEAD'], $uri, $callback);
	}

	/**
	 * Register a new POST route
	 * 
	 * @param  string   $uri      
	 * @param  callable $callback 
	 */
	public function post(string $uri, array|callable $callback): void
	{
		$this->registerRoute('POST', $uri, $callback);
	}

	/**
	 * Register a new PUT route
	 * 
	 * @param  string   $uri      
	 * @param  callable $callback
	 */
	public function put(string $uri, array|callable $callback): void
	{
		$this->registerRoute('PUT', $uri, $callback);
	}

	/**
	 * Register a new DELETE route
	 * 
	 * @param  string   $uri      
	 * @param  callable $callback 
	 */
	public function delete(string $uri, array|callable $callback): void
	{
		$this->registerRoute('DELETE', $uri, $callback);
	}

	/**
	 * Matches the request to the appropriate callback
	 * 
	 * @param  string $method 
	 * @param  string $uri    
	 */
	public function dispatch(Request $request)
	{
		foreach ($this->routes as $route)
		{
			if ($route['uri'] == $request->getUri())
			{
				if (in_array($request->getMethod(), $route['methods']))
				{
					return call_user_func($route['callback'], $request);
				}

				throw new \Exception("Unsupported method");
			}
		}
		
		throw new \Exception("Route undefined");
	}

	private function invoke(array|callable $callback, Request $request)
	{
		if (is_array($callback))
		{
			list($class, $method) = $callback;

			if (!class_exists($class))
			{
				throw new \Exception("$class not defined");
			}

			$controller = new $class;

			if (!method_exists($controller, $method))
			{
				throw new \Exception("$method not defined");
			}

			return call_user_func([$controller, $method], $request);
		}

		if (is_callable($callback))
		{
			return call_user_func($callback, $request);
		}

		throw new \Exception("Invalid callback for route");
	}

	/**
	 * Attempt to register a new route
	 * 
	 * @param  string   $method 
	 * @param  string   $uri    
	 */
	private function registerRoute(string|array $verb, string $uri, array|callable $callback): void
	{	
		$methods = (array) $verb;

		foreach ($methods as $method)
		{
			if ($this->routeRegistered($method, $uri))
			{
				throw new \Exception("Cannot redeclare route $uri");
			}
		}

		$this->addRoute($methods, $uri, $callback);
	}

	/**
	 * Register a new route
	 * 
	 * @param array    $methods 
	 * @param string   $uri     
	 * @param callable $callback
	 */
	private function addRoute(array $methods, string $uri, array|callable $callback): void
	{
		$this->routes[] = [
			'methods' => $methods,
			'uri' => $uri,
			'callback' => $callback
		];
	}

	/**
	 * Check if a route is registered
	 * 
	 * @param  string $method 
	 * @param  string $uri    
	 */
	private function routeRegistered(string $method, string $uri): bool
	{
		foreach ($this->routes as $route)
		{
			if (in_array($method, $route['methods']) && $route['uri'] === $uri) 
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Return available methods for the class
	 * 
	 * @return array
	 */
	private static function getAvailableMethods(): array
	{
		$reflectionClass = new \ReflectionClass(self::class);

		$methods = $reflectionClass->getMethods();

		$availableMethods = [];

		foreach($methods as $method)
		{
			/**
			 *  TODO:
			 *  	- Avoid magic methods
			 */
			if(!$method->isConstructor() && !$method->isDestructor())
			{
				$methodNames[] = $method->getName();
			}
		}

		return $methodNames;
	}
}