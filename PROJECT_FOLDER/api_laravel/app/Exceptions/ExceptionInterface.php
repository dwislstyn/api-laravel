<?php declare(strict_types=1);

namespace App\Exceptions;

interface ExceptionInterface
{
	public function getData() : mixed;

	public function getErrorCode() : string;
}