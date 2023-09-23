<?php

namespace ShockedPlot7560\PmmpUnit\framework;

use React\Promise\PromiseInterface;
use Throwable;

trait CommonTestFunctions {
	use ExceptionExpectationTrait;

	public function onLoad() : void {
		$method = $this->class->getMethod("onLoad");
		if ($method->isPublic() && !$method->isAbstract()) {
			$method->invoke($this->getInstance());
		}
	}

	public function onEnable() : void {
		$method = $this->class->getMethod("onEnable");
		if ($method->isPublic() && !$method->isAbstract()) {
			$method->invoke($this->getInstance());
		}
	}

	public function onDisable() : void {
		$method = $this->class->getMethod("onDisable");
		if ($method->isPublic() && !$method->isAbstract()) {
			$method->invoke($this->getInstance());
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
				return $this->invokeTest($test)
					->finally(fn (mixed $ret = null) => $this->tearDown($test, $ret));
			})
			->then(function () {
				$this->expectedExceptionWasNotRaised();

				return null;
			})
			->catch(function (Throwable $th) {
				if (!$this->shouldExceptionExpectationsBeVerified($th)) {
					throw $th;
				}

				$this->verifyExceptionExpectations($th);
			})
			->catch(fn (mixed $ret = null) => $this->tearDown($test, $ret));
	}

	/**
	 * @return PromiseInterface<null>
	 */
	private function invokeTest(TestCase $test) : PromiseInterface {
		return $this->method->invoke($test);
	}
	private ?TestCase $instance = null;

	private function getInstance(bool $regenerate = true) : TestCase {
		if ($this->instance === null || $regenerate) {
			$this->instance = $this->class->newInstance($this);
		}

		return $this->instance;
	}
}
