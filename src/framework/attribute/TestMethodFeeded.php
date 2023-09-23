<?php

namespace ShockedPlot7560\PmmpUnit\framework\attribute;

use React\Promise\PromiseInterface;
use ReflectionClass;
use ReflectionMethod;
use function Respect\Stringifier\stringify;
use ShockedPlot7560\PmmpUnit\framework\CommonTestFunctions;
use ShockedPlot7560\PmmpUnit\framework\ExceptionExpectationHandler;
use ShockedPlot7560\PmmpUnit\framework\RunnableTest;
use ShockedPlot7560\PmmpUnit\framework\TestCase;

class TestMethodFeeded implements RunnableTest, ExceptionExpectationHandler {
	use CommonTestFunctions;

	/**
	 * @param ReflectionClass<TestCase> $class
	 * @param iterable<mixed> $args
	 */
	public function __construct(
		private ReflectionClass $class,
		private ReflectionMethod $method,
		private iterable $args
	) {
	}

	/**
	 * @return PromiseInterface<null>
	 */
	private function invokeTest(TestCase $test) : PromiseInterface {
		return $this->method->invoke($test, ...$this->args);
	}

	public function __toString() : string {
		return $this->class->getName() . "::" . $this->method->getName() . "(" .
			implode(", ", array_map(fn ($v) => stringify($v), $this->args))
			. ")";
	}
}
