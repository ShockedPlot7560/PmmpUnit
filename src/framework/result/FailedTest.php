<?php

namespace ShockedPlot7560\PmmpUnit\framework\result;

use ShockedPlot7560\PmmpUnit\framework\RunnableTest;
use Throwable;

class FailedTest implements TestResult {
	public function __construct(
		public readonly RunnableTest $test,
		public readonly Throwable $throwable
	) {
	}
}
