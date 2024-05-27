<?php

namespace Tersworks\Foundation\Http;

class Request
{
	protected $method;
	protected $uri;
	protected $headers;
	protected $query;
	protected $body;
	protected $files;

	private function __construct(string $method, string $uri, array $headers, ?array $query = [], ?array $body = [], ?array $files = [])
	{
		$this->method = $method;
		$this->uri = $uri;
		$this->headers = $headers;
		$this->query = $query;
		$this->body = $body;
		$this->files = $files;
	}

	public static function capture(): Request
	{
		$method = $_SERVER['REQUEST_METHOD'];
		$uri = $_SERVER['REQUEST_URI'];
		$headers = getallheaders();
		$query = $_GET;
		$body = self::getRequestBody();
		$files = $_FILES;

		return new self($method, $uri, $headers, $query, $body, $files);
	}

	protected static function getRequestBody(): array
	{
		$body = [];

		if ($_SERVER['REQUEST_METHOD'] == 'POST' && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false)
		{
			$body = json_decode(file_get_contents('php://input'), true);
		}
		else
		{
			$body = $_POST;
		}

		return $body;
	}

	public function getMethod(): string
	{
		return $this->method;
	}

	public function getUri(): string
	{
		return $this->uri;
	}
	
	public function getHeaders(): array
	{
		return $this->headers;
	}

	public function getQuery(): ?array
	{
		return $this->query;
	}

	public function getBody(): ?array
	{
		return $this->body;
	}

	public function getFiles(): ?array
	{
		return $this->files;
	}
}