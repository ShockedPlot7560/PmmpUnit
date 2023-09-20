<?php

namespace ShockedPlot7560\UnitTest\framework\result;

use ShockedPlot7560\UnitTest\framework\TestMethod;

readonly class SuccessTest {
	public function __construct(
		public TestMethod $test
	) {
	}
}
