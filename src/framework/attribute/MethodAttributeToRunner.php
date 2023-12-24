<?php

namespace ShockedPlot7560\PmmpUnit\framework\attribute;

use ReflectionClass;
use ReflectionMethod;
use ShockedPlot7560\PmmpUnit\framework\runner\TestRunnerInterface;
use ShockedPlot7560\PmmpUnit\framework\TestCase;

interface MethodAttributeToRunner {
	/**
	 * @param ReflectionClass<TestCase> $class
	 */
	public function getRunner(ReflectionClass $class, ReflectionMethod $method) : TestRunnerInterface;
}
