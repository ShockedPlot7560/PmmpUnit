<?php

namespace ShockedPlot7560\PmmpUnit\framework\listener;

use pocketmine\event\Listener;
use ShockedPlot7560\PmmpUnit\framework\event\TestEndEvent;
use ShockedPlot7560\PmmpUnit\framework\event\TestFailedEvent;
use ShockedPlot7560\PmmpUnit\framework\event\TestStartEvent;
use ShockedPlot7560\PmmpUnit\framework\event\TestSuccessEvent;
use ShockedPlot7560\PmmpUnit\framework\result\TestResults;
use ShockedPlot7560\PmmpUnit\framework\TestMemory;

class TestRunnerListener implements Listener {
	public function onTestStart(TestStartEvent $event) : void {
		TestMemory::$currentTest = $event->getTest();
	}

	public function onTestSuccess(TestSuccessEvent $event) : void {
		TestResults::successTest($event->getTest());
	}

	public function onTestEnd(TestEndEvent $event) : void {
		TestMemory::$currentTest = null;
	}

	public function onTestFailed(TestFailedEvent $event) : void {
		TestResults::failedTest($event->getTest(), $event->getThrowable());
	}
}
