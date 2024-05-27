<?php

namespace Tersworks\Facades;

use Tersworks\Foundation\Container;

abstract class Facade
{
	abstract public static function getFacadeAccessor(): string;
	abstract public static function getFacadeClass(): string;

	public static function __callStatic(string $method, array $args): mixed
	{
		$instance = Container::get(static::getFacadeAccessor());

		if($instance === null)
		{
			throw new \Exception("Unknown facade " . static::getFacadeAccessor());
		}

		return $instance->$method(...$args);
	}
}