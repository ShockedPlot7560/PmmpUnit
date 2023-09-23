<?php

namespace ShockedPlot7560\PmmpUnit\framework\attribute;

use React\Promise\PromiseInterface;
use ReflectionClass;
use ReflectionMethod;
use ShockedPlot7560\PmmpUnit\framework\CommonTestFunctions;
use ShockedPlot7560\PmmpUnit\framework\ExceptionExpectationHandler;
use ShockedPlot7560\PmmpUnit\framework\RunnableTest;
use ShockedPlot7560\PmmpUnit\framework\TestCase;
use Stringable;

class TestMethodFeeded implements RunnableTest, ExceptionExpectationHandler {
	use CommonTestFunctions;

	/**
	 * @param ReflectionClass<TestCase> $class
	 */
	public function __construct(
		private ReflectionClass $class,
		private ReflectionMethod $method,
		private array $args
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
			implode(", ", array_map(fn ($v) => $this->stringifyArg($v), $this->args))
			. ")";
	}

	private function stringifyArg(mixed $value) : string {
		if (is_object($value)) {
			if ($value instanceof Stringable) {
				return (string) $value;
			}

			return get_class($value);
		}
		if (is_array($value)) {
			return "array(" . implode(", ", array_map(fn ($v) => $this->stringifyArg($v), $value)) . ")";
		}
		if (is_string($value)) {
			return '"' . $value . '"';
		}
		if (is_bool($value)) {
			return $value ? "true" : "false";
		}
		if (is_null($value)) {
			return "null";
		} else {
			return (string) $value;
		}
	}
}
