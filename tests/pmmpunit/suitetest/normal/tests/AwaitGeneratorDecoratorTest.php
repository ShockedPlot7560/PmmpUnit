<?php

namespace ShockedPlot7560\PmmpUnit\tests\normal;

use Exception;
use Generator;
use pocketmine\scheduler\Task;
use React\Promise\PromiseInterface;
use ShockedPlot7560\PmmpUnit\framework\TestCase;
use ShockedPlot7560\PmmpUnit\PmmpUnit;
use ShockedPlot7560\PmmpUnit\utils\AwaitGeneratorDecorator;
use SOFe\AwaitGenerator\Await;

class AwaitGeneratorDecoratorTest extends TestCase {
	public function testClosureCallback() : PromiseInterface {
		$start = microtime(true);

		$decorator = new AwaitGeneratorDecorator(function () : Generator {
			return $this->sleep();
		});

		return $decorator->then(function () use ($start) : void {
			$this->assertTrue(microtime(true) - $start >= 0.9);
		});
	}

	public function testGeneratorCallback() : PromiseInterface {
		$start = microtime(true);

		$decorator = new AwaitGeneratorDecorator($this->sleep());

		return $decorator->then(function () use ($start) : void {
			$this->assertTrue(microtime(true) - $start >= 0.9);
		});
	}

	private function sleep() : Generator {
		yield from Await::promise(function ($resolve, $reject) {
			$task = new class($resolve, $reject) extends Task {
				private $resolve;
				private $reject;

				public function __construct($resolve, $reject) {
					$this->resolve = $resolve;
					$this->reject = $reject;
				}

				public function onRun() : void {
					($this->resolve)();
				}

				public function onCancel() : void {
					($this->reject)(new Exception("Task cancelled"));
				}
			};
			PmmpUnit::getInstance()->getScheduler()->scheduleDelayedTask($task, 20);
		});
	}
}
