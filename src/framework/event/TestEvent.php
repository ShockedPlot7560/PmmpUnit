<?php

namespace ShockedPlot7560\PmmpUnit\framework\event;

use pocketmine\event\Event;
use ShockedPlot7560\PmmpUnit\framework\runner\TestRunnerInterface;

abstract class TestEvent extends Event {
	public function __construct(
		protected TestRunnerInterface $test
	) {
	}

	public function getTest() : TestRunnerInterface {
		return $this->test;
	}
}
