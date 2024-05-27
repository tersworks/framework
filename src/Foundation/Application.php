<?php

namespace Tersworks\Foundation;

use Tersworks\Facades\Route;
use Tersworks\Foundation\Http\Request;

class Application extends Container
{
	private static ?Application $instance = null;

	const VERSION = '0.1-alpha';

	protected string $basePath;

	private function __construct(string $path)
	{
		$this->basePath = $path;
	}

	public static function configure(string $path): Application
	{
		if(self::$instance === null)
		{
			self::$instance = new self($path);
		}

		return self::$instance;
	}

	public static function version(): string
	{
		return self::VERSION;
	}

	public function handleRequest(Request $request)
	{
		return Route::dispatch($request);
	}
}