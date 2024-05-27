<?php

namespace Tersworks\Foundation;

use Tersworks\Facades\Route;
use Tersworks\Exceptions\Handler;
use Tersworks\Foundation\Http\Request;

class Application extends Container
{
	private static ?Application $instance = null;

	const VERSION = '0.1.3-alpha';

	protected string $basePath;

	private function __construct(string $path)
	{
		$this->basePath = $path;

		static::registerFacades([
			\Tersworks\Facades\Route::class
		]);
	}

	public static function configure(string $path): Application
	{
		if (self::$instance === null)
		{
			self::$instance = new self($path);
		}

		return self::$instance;
	}

	public function withRoutes(string $path): Application
	{
		if (!file_exists($path))
		{
			throw new Exception("The specified file doesn't exist.");
		}

		require $path;

		return $this;
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