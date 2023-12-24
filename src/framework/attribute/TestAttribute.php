<?php

namespace ShockedPlot7560\PmmpUnit\framework\attribute;

use Attribute;
use React\Promise\PromiseInterface;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use RuntimeException;
use ShockedPlot7560\PmmpUnit\framework\runner\TestMethodRunner;
use ShockedPlot7560\PmmpUnit\framework\runner\TestRunnerInterface;

#[Attribute(Attribute::TARGET_METHOD)]
class TestAttribute implements MethodAttributeToRunner {
	public function getRunner(ReflectionClass $class, ReflectionMethod $method) : TestRunnerInterface {
		$this->check($method);

		return new TestMethodRunner($class, $method);
	}

	private function check(ReflectionMethod $method) : void {
		if (!$method->isPublic()) {
			throw new RuntimeException("Test method " . $method->getName() . " is not public!");
		}
		$returnType = $method->getReturnType();

		if (!$returnType instanceof ReflectionNamedType || $returnType->getName() !== PromiseInterface::class || $returnType->allowsNull()) {
			throw new RuntimeException("Test method " . $method->getName() . " must return a PromiseInterface!");
		}
	}
}
