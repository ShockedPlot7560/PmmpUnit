<?php

namespace ShockedPlot7560\UnitTest\framework;

use React\Promise\PromiseInterface;
use ReflectionClass;
use ReflectionMethod;

class TestMethod implements RunnableTest {
	public function __construct(
		private ReflectionClass $class,
		private ReflectionMethod $method,
	) {
	}

	public function run() : PromiseInterface {
		$method = $this->method;

		return $method->invoke($this->class->newInstanceWithoutConstructor());
	}

	public function __toString() : string {
		return $this->class->getName() . "::" . $this->method->getName();
	}
}
