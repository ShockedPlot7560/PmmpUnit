<?php

namespace ShockedPlot7560\PmmpUnit\framework;

use Throwable;
interface ExceptionExpectationHandler {
	/**
	 * @phpstan-param class-string<Throwable> $exception
	 */
	public function expectException(string $exception) : void;

	public function expectExceptionCode(int|string $code) : void;

	public function expectExceptionMessage(string $message) : void;

	public function expectExceptionMessageMatches(string $regularExpression) : void;
}
