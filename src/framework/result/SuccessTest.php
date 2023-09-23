<?php

namespace ShockedPlot7560\PmmpUnit\framework\result;

use ShockedPlot7560\PmmpUnit\framework\TestMethod;

class SuccessTest implements TestResult {
	public function __construct(
		public readonly TestMethod $test
	) {
	}
}
