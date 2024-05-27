<?php

namespace Tersworks\Exceptions;

class Handler
{
	private static ?Handler $instance = null;

	private function __construct() 
	{
		@set_error_handler([$this, 'handle']);
	}

    public static function __callStatic(string $name, array $arguments)
    {
        $handler = self::getInstance();

        if (is_callable([$handler, $name])) {
            return call_user_func_array([$handler, $name], $arguments);
        }

        throw new \BadMethodCallException("Method {$name} not found");
    }

	public static function getInstance(): Handler
	{
		if (self::$instance === null)
		{
			self::$instance == new self();
		}

		return self::$instance;
	}

	protected function handle(Throwable $exception)
	{
		echo 'An error occurred. Please try again later!';
	}
}