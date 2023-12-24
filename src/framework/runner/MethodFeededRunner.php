<?php

namespace ShockedPlot7560\PmmpUnit\framework\runner;

use React\Promise\PromiseInterface;
use ReflectionClass;
use ReflectionMethod;
use function Respect\Stringifier\stringify;
use ShockedPlot7560\PmmpUnit\framework\TestCase;

class MethodFeededRunner extends EndChildRunner {
	/** @var mixed[]  */
	private array $args;

	/**
	 * @param ReflectionClass<TestCase> $class
	 * @param iterable<mixed> $args
	 */
	public function __construct(
		ReflectionClass $class,
		ReflectionMethod $method,
		iterable $args,
		private ?string $name = null
	) {
		parent::__construct($class, $method);
		$this->args = is_array($args) ? $args : iterator_to_array($args);
	}

	/**
	 * @return PromiseInterface<null>
	 */
	protected function invokeTest(TestCase $test) : PromiseInterface {
		return $this->method->invoke($test, ...$this->args);
	}

	public function __toString() : string {
		return $this->name ?? $this->class->getName() . "::" . $this->method->getName() . "(" .
			implode(", ", array_map(fn ($v) => stringify($v), $this->args))
		. ")";
	}
}
