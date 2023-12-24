<?php

namespace ShockedPlot7560\PmmpUnit\framework\event;

use ShockedPlot7560\PmmpUnit\framework\runner\TestRunnerInterface;
use Throwable;

class TestFailedEvent extends TestEvent {
	public function __construct(
		TestRunnerInterface $test,
		private Throwable  $throwable
	) {
		parent::__construct($test);
	}

	public function getThrowable() : Throwable {
		return $this->throwable;
	}
}
