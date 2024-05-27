<?php

namespace Tersworks\Facades;

use Tersworks\Foundation\Router;

class Route extends Facade
{
	public static function getFacadeAccessor(): string
	{
		return 'Router';
	}

	public static function getFacadeClass(): string
	{
		return Router::class;
	}
}