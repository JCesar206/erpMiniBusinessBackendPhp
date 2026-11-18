<?php
class HttpException extends RuntimeException
{
	private int $status;
	public function __construct(string $message, int $status)
	{
		parent::__construct($message);
		$this->status = $status;
	}
	public function getStatus(): int
	{
		return $this->status;
	}
}
