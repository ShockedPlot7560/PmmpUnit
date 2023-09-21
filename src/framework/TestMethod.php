<?php

namespace ShockedPlot7560\UnitTest\framework;

use React\Promise\PromiseInterface;
use ReflectionClass;
use ReflectionMethod;
use Throwable;

class TestMethod implements RunnableTest {
	public function __construct(
		private ReflectionClass $class,
		private ReflectionMethod $method,
	) {
	}

	public function run() : PromiseInterface {
		$test = $this->class->newInstanceWithoutConstructor();

		return $this->setUp($test)
			->then(function () use ($test) : PromiseInterface {
				return $this->method->invoke($test)
					->finally(fn (mixed $ret = null) => $this->tearDown($test, $ret));
			})
			->catch(fn (mixed $ret = null) => $this->tearDown($test, $ret));
	}

	/**
	 * @return PromiseInterface<null>
	 */
	public function tearDown(TestCase $test, mixed $exception = null) : PromiseInterface {
		$tearDownMethod = $this->class->getMethod("tearDown");

		return $tearDownMethod->invoke($test)
			->finally(function () use ($exception) : void {
				if ($exception !== null && $exception instanceof Throwable) {
					throw $exception;
				}
			});
	}

	/**
	 * @return PromiseInterface<null>
	 */
	public function setUp(TestCase $test) : PromiseInterface {
		$setUpMethod = $this->class->getMethod("setUp");

		return $setUpMethod->invoke($test);
	}

	public function __toString() : string {
		return $this->class->getName() . "::" . $this->method->getName();
	}

	public function onLoad() : void {
		$method = $this->class->getMethod("onLoad");
		if ($method->isPublic() && !$method->isAbstract()) {
			$method->invoke($this->class->newInstanceWithoutConstructor());
		}
	}

	public function onEnable() : void {
		$method = $this->class->getMethod("onEnable");
		if ($method->isPublic() && !$method->isAbstract()) {
			$method->invoke($this->class->newInstanceWithoutConstructor());
		}
	}

	public function onDisable() : void {
		$method = $this->class->getMethod("onDisable");
		if ($method->isPublic() && !$method->isAbstract()) {
			$method->invoke($this->class->newInstanceWithoutConstructor());
		}
	}
}
