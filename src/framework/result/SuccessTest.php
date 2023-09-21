<?php

namespace ShockedPlot7560\UnitTest\framework\result;

use ShockedPlot7560\UnitTest\framework\TestMethod;

class SuccessTest implements TestResult {
	public function __construct(
		public readonly TestMethod $test
	) {
	}
}
