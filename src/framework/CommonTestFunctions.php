<?php

namespace ShockedPlot7560\PmmpUnit\framework;

use pocketmine\Server;
use React\Promise\PromiseInterface;
use function React\Promise\resolve;
use Throwable;

trait CommonTestFunctions {
	private ExpectedExceptionHandler $expectedExceptionHandler;
	private ?TestCase $instance = null;

	public function onLoad() : void {
		if (TestMemory::$loadedClasses[$this->class->getName()] ?? false) {
			return;
		}
		$method = $this->class->getMethod("onLoad");
		if ($method->isPublic() && !$method->isAbstract()) {
			$method->invoke($this->getInstance());

			TestMemory::$loadedClasses[$this->class->getName()] = true;
		}
	}

	public function onEnable() : void {
		if (TestMemory::$enabledClasses[$this->class->getName()] ?? false) {
			return;
		}
		$method = $this->class->getMethod("onEnable");
		if ($method->isPublic() && !$method->isAbstract()) {
			$method->invoke($this->getInstance());

			TestMemory::$enabledClasses[$this->class->getName()] = true;
		}
	}

	public function onDisable() : void {
		if (TestMemory::$disabledClasses[$this->class->getName()] ?? false) {
			return;
		}
		$method = $this->class->getMethod("onDisable");
		if ($method->isPublic() && !$method->isAbstract()) {
			$method->invoke($this->getInstance());

			TestMemory::$disabledClasses[$this->class->getName()] = true;
		}
	}

	/**
	 * @return PromiseInterface<null>
	 */
	public function setUp(object $test) : PromiseInterface {
		$setUpMethod = $this->class->getMethod("setUp");

		return $setUpMethod->invoke($test);
	}

	/**
	 * @return PromiseInterface<null>
	 */
	public function tearDown(object $test, mixed $exception = null) : PromiseInterface {
		$tearDownMethod = $this->class->getMethod("tearDown");

		return $tearDownMethod->invoke($test)
			->finally(function () use ($exception) : void {
				if ($exception !== null && $exception instanceof Throwable) {
					throw $exception;
				}
			});
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	public function run() : PromiseInterface {
		$test = $this->getInstance(false);

		return $this->setUp($test)
			->then(function () use ($test) : PromiseInterface {
				Server::getInstance()->getLogger()->debug("Testing : " . $this->__toString());

				return $this->invokeTest($test)
					->finally(function () use ($test) {
						$this->tearDown($test);
					});
			})
			->then(function () use ($test) {
				$test->getExceptionHandler()->expectedExceptionWasNotRaised();

				return resolve(null);
			})
			->catch(function (Throwable $th) use ($test) {
				if (!$test->getExceptionHandler()->shouldExceptionExpectationsBeVerified($th)) {
					throw $th;
				}

				$test->getExceptionHandler()->verifyExceptionExpectations($th);

				return null;
			})
			->catch(fn (mixed $ret = null) => $this->tearDown($test, $ret));
	}

	/**
	 * @return PromiseInterface<null>
	 */
	private function invokeTest(TestCase $test) : PromiseInterface {
		return $this->method->invoke($test);
	}

	private function getInstance(bool $regenerate = true) : TestCase {
		if ($this->instance === null || $regenerate) {
			$this->instance = $this->class->newInstance(new ExpectedExceptionHandler($this->method));
		}

		return $this->instance;
	}
}
