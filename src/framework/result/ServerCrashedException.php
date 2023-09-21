<?php

namespace ShockedPlot7560\UnitTest\framework\result;

use Exception;

class ServerCrashedException extends Exception {
	/**
	 * @param array<string, mixed> $array
	 */
	public static function fromArray(array $array) : Exception {
		$exception = new self();
		$exception->message = $array["message"];
		$exception->code = $array["code"];
		$exception->file = $array["file"];
		$exception->line = $array["line"];

		return $exception;
	}
}
