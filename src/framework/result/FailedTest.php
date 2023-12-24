<?php

namespace ShockedPlot7560\PmmpUnit\framework\result;

use ShockedPlot7560\PmmpUnit\framework\runner\TestRunnerInterface;
use Throwable;

class FailedTest implements TestResult {
	public function __construct(
		public readonly TestRunnerInterface $test,
		public readonly Throwable $throwable
	) {
	}
}
