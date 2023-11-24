<?php

namespace ShockedPlot7560\PmmpUnit\tests\normal;

use Closure;
use Exception;
use Generator;
use pocketmine\scheduler\Task;
use React\Promise\PromiseInterface;
use ShockedPlot7560\PmmpUnit\framework\TestCase;
use ShockedPlot7560\PmmpUnit\PmmpUnit;
use ShockedPlot7560\PmmpUnit\utils\AwaitGeneratorDecorator;
use SOFe\AwaitGenerator\Await;

class AwaitGeneratorDecoratorTest extends TestCase {
	/**
	 * @phpstan-return PromiseInterface<void>
	 */
	public function testClosureCallback() : PromiseInterface {
		$start = microtime(true);

		$decorator = new AwaitGeneratorDecorator(function () : Generator {
			return $this->sleep();
		});

		return $decorator->then(function () use ($start) : void {
            var_dump(microtime(true) - $start);
			$this->assertTrue(microtime(true) - $start >= 0.9);
		});
	}

	/**
	 * @phpstan-return PromiseInterface<void>
	 */
	public function testGeneratorCallback() : PromiseInterface {
		$start = microtime(true);

		$decorator = new AwaitGeneratorDecorator($this->sleep());

		return $decorator->then(function () use ($start) : void {
			$this->assertTrue(microtime(true) - $start >= 0.8);
		});
	}

	private function sleep() : Generator {
		yield from Await::promise(function ($resolve, $reject) {
			$task = new class($resolve, $reject) extends Task {
				public function __construct(
					private Closure $resolve,
					private Closure $reject
				) {
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
