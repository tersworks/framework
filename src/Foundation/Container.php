<?php

namespace Tersworks\Foundation;

abstract class Container
{
	public static array $bindings = [];
	public static array $instances = [];

	public static function bind(string $name, string $className): void
	{
		static::$bindings[$name] = $className;
	}

	public static function set(string $name, mixed $instance): void
	{
		static::$instances[$name] = $instance;
	}

	public static function get(string $name): mixed
	{
		if (!isset(static::$instances[$name]))
		{
			if(isset(static::$bindings[$name]))
			{
				$className = static::$bindings[$name];
				static::set($name, $className::getInstance());
			}
			else
			{
				return null;
			}
		}

		return static::$instances[$name];
	}

	public static function registerFacades(array $facades): void
	{
		foreach ($facades as $facade)
		{
			static::bind($facade::getFacadeAccessor(), $facade::getFacadeClass());
		}
	}
}