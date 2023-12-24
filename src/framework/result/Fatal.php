<?php

namespace ShockedPlot7560\PmmpUnit\framework\result;

use Stringable;
use Throwable;

class Fatal implements TestResult, ThrowableResult, Stringable {
	public function __construct(
		public readonly Throwable $throwable
	) {
	}

	public function getThrowable() : Throwable {
		return $this->throwable;
	}

	public function __toString() : string {
		return "Runtime exception";
	}
}
