<?php

namespace Tersworks\Exceptions;

class Handler
{
	private static ?Handler $instance = null;

	private function __construct() 
	{
		set_exception_handler([$this, 'handle']);
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
		return self::$instance ?? self::$instance = new self();
	}

	public function handle(\Throwable $exception)
	{
		echo $exception->getMessage();
	}
}