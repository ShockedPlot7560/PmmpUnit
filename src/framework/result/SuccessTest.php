<?php

namespace ShockedPlot7560\PmmpUnit\framework\result;

use ShockedPlot7560\PmmpUnit\framework\RunnableTest;

class SuccessTest implements TestResult {
	public function __construct(
		public readonly RunnableTest $test
	) {
	}
}
