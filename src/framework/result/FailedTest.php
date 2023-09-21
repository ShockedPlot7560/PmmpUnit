<?php

namespace ShockedPlot7560\UnitTest\framework\result;

use ShockedPlot7560\UnitTest\framework\TestMethod;
use Throwable;

class FailedTest implements TestResult {
	public function __construct(
		public readonly TestMethod $test,
		public readonly Throwable $throwable
	) {
	}
}
