<?php

namespace ShockedPlot7560\PmmpUnit\framework\result;

use ShockedPlot7560\PmmpUnit\framework\runner\TestRunnerInterface;
use Throwable;

class FailedTest implements TestResult, ThrowableResult, \Stringable {
	public function __construct(
		public readonly TestRunnerInterface $test,
		public readonly Throwable $throwable
	) {
	}

    public function getThrowable(): Throwable {
        return $this->throwable;
    }

    public function __toString(): string
    {
        return $this->test->__toString();
    }
}
