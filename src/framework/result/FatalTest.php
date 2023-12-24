<?php

namespace ShockedPlot7560\PmmpUnit\framework\result;

use ShockedPlot7560\PmmpUnit\framework\runner\TestRunnerInterface;
use Throwable;

class FatalTest extends Fatal {
	public function __construct(
		public readonly TestRunnerInterface $test,
		Throwable $throwable
	) {
		parent::__construct($throwable);
	}

	public function __toString() : string {
		return $this->test->__toString();
	}
}
