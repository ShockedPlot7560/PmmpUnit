<?php

namespace ShockedPlot7560\UnitTest\framework\result;

use ShockedPlot7560\UnitTest\framework\TestMethod;
use Throwable;

readonly class FatalTest {
	public function __construct(
		public TestMethod $test,
		public Throwable $throwable
	) {
	}
}
