<?php

namespace ShockedPlot7560\PmmpUnit\tests\normal;

use Closure;
use Exception;
use Generator;
use pocketmine\scheduler\AsyncTask;
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
		$decorator = new AwaitGeneratorDecorator(function () : Generator {
			return $this->asyncTask();
		});

		return $decorator->then(function () : void {
			$this->assertTrue(true);
		})->catch(function (Exception $e) : void {
			$this->assertTrue(false, $e->getMessage());
		});
	}

	/**
	 * @phpstan-return PromiseInterface<void>
	 */
	public function testGeneratorCallback() : PromiseInterface {
		$decorator = new AwaitGeneratorDecorator($this->asyncTask());

		return $decorator->then(function () : void {
			$this->assertTrue(true);
		})->catch(function (Exception $e) : void {
			$this->assertTrue(false, $e->getMessage());
		});
	}

	private function asyncTask() : Generator {
		yield from Await::promise(function ($resolve, $reject) {
			$task = new class($resolve, $reject) extends AsyncTask {
				public function __construct(
					Closure $resolve,
					Closure $reject
				) {
					$this->storeLocal("resolve", $resolve);
					$this->storeLocal("reject", $reject);
				}

				public function onRun() : void {
					sleep(1);
					$this->setResult(true);
				}

				public function onCompletion() : void {
					($this->fetchLocal("resolve"))($this->getResult());
				}
			};
			PmmpUnit::getInstance()->getServer()->getAsyncPool()->submitTask($task);
		});
	}
}
