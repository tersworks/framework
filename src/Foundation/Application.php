<?php

namespace Tersworks\Foundation;

class Application extends Container
{
	const VERSION = '0.1-alpha';

	public static function version(): string
	{
		return self::VERSION;
	}
}