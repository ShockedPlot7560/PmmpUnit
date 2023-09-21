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
		return $this->setUp()
			->then(function () : PromiseInterface {
				return $this->method->invoke($this->class->newInstanceWithoutConstructor())
					->finally(fn (mixed $ret = null) => $this->tearDown($ret));
			})
			->catch(fn (mixed $ret = null) => $this->tearDown($ret));
	}

	/**
	 * @return PromiseInterface<null>
	 */
	public function tearDown(mixed $exception = null) : PromiseInterface {
		$tearDownMethod = $this->class->getMethod("tearDown");

		return $tearDownMethod->invoke($this->class->newInstanceWithoutConstructor())
			->finally(function () use ($exception) : void {
				if ($exception !== null && $exception instanceof Throwable) {
					throw $exception;
				}
			});
	}

	/**
	 * @return PromiseInterface<null>
	 */
	public function setUp() : PromiseInterface {
		$setUpMethod = $this->class->getMethod("setUp");

		return $setUpMethod->invoke($this->class->newInstanceWithoutConstructor());
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
