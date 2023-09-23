<?php

namespace ShockedPlot7560\PmmpUnit\framework\result;

use ShockedPlot7560\PmmpUnit\framework\TestMethod;
use Throwable;

class FatalTest implements TestResult {
	public function __construct(
		public readonly TestMethod $test,
		public readonly Throwable $throwable
	) {
	}
}
