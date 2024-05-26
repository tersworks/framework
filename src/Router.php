<?php

namespace Tersworks;

final class Router
{	
	/**
	 * @var array The list of routes.
	 */
	private $routes = [];

	/**
	 * Register a new GET route
	 * 
	 * @param  string   $uri      The URI pattern
	 * @param  callable $callback Callback or controller action to handle the request
	 */
	
	public function get(string $uri, array|callable $callback): void 
	{
		$this->registerRoute('GET', $uri, $callback);
	}

	/**
	 * Register a new POST route
	 * 
	 * @param  string   $uri      The URI pattern
	 * @param  callable $callback Callback or controller action to handle the request
	 */
	public function post(string $uri, array|callable $callback): void
	{
		$this->registerRoute('POST', $uri, $callback);
	}

	/**
	 * Register a new PUT route
	 * 
	 * @param  string   $uri      The URI pattern
	 * @param  callable $callback Callback or controller action to handle the request
	 */
	public function put(string $uri, array|callable $callback): void
	{
		$this->registerRoute('PUT', $uri, $callback);
	}

	/**
	 * Register a new DELETE route
	 * 
	 * @param  string   $uri      The URI pattern
	 * @param  callable $callback Callback or controller action to handle the request
	 */
	public function delete(string $uri, array|callable $callback): void
	{
		$this->registerRoute('DELETE', $uri, $callback);
	}

	/**
	 * Register a new route
	 * 
	 * @param  string   $method   [description]
	 * @param  string   $uri      [description]
	 * @param  callable $callback [description]
	 */
	private function registerRoute(string $method, string $uri, array|callable $callback): void
	{
		if($this->routeRegistered($method, $uri))
		{
			throw new \Exception("Cannot redeclare route $uri");
		}
	}

	/**
	 * Check if a route is registered
	 * 
	 * @param  string $method [description]
	 * @param  string $uri    [description]
	 * @return [type]         [description]
	 */
	private function routeRegistered(string $method, string $uri): bool
	{
		foreach ($this->routes as $route)
		{
			if($route['method'] === $method && $route['uri'] === $uri) 
			{
				return true;
			}
		}

		return false;
	}
}