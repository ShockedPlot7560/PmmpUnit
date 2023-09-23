<?php

namespace ShockedPlot7560\PmmpUnit\framework;

use ReflectionClass;
use ReflectionMethod;

class TestMethod implements RunnableTest {
	use CommonTestFunctions;

	/**
	 * @param ReflectionClass<TestCase> $class
	 */
	public function __construct(
		private ReflectionClass $class,
		private ReflectionMethod $method,
	) {
	}

	public function __toString() : string {
		return $this->class->getName() . "::" . $this->method->getName();
	}
}
