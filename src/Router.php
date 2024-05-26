<?php

namespace Tersworks;

final class Router
{	
	private static ?Router $instance = null;

	/**
	 * @var array The list of routes.
	 */
	private array $routes = [];

	private function __construct() {}
	private function __clone() {}

	public static function __callStatic(string $name, array $arguments): mixed
	{
		if (in_array($name, self::getAvailableMethods()))
		{
			return self::getInstance()->$name(...$arguments);
		}

		throw new \Exception("Unknown method $name");
	}

	public static function getInstance(): Router
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
	public function dispatch(string $method, string $uri)
	{
		foreach ($this->routes as $route)
		{
			if (in_array($method, $route['methods']) && $route['uri'] == $uri)
			{
				return call_user_func($route['callback']);
			}
		}

		echo "Route undefined";
		throw new \Exception("Route undefined");
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
			if ($route['method'] === $method && $route['uri'] === $uri) 
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Return available methods for the class
	 * @return array
	 */
	private static function getAvailableMethods(): array
	{
		$reflectionClass = new \ReflectionClass(self::class);

		$methods = $reflectionClass->getMethods();

		$availableMethods = [];

		foreach($methods as $method)
		{
			if(!$method->isConstructor() && !$method->isDestructor() && !$method->isMagic())
			{
				$methodNames[] = $method->getName();
			}
		}

		return $methodNames;
	}
}