<?php

namespace ShockedPlot7560\PmmpUnit\framework\attribute;

use ReflectionClass;
use ReflectionMethod;
use ShockedPlot7560\PmmpUnit\framework\runner\TestRunnerInterface;

interface MethodAttributeToRunner {
	public function getRunner(ReflectionClass $class, ReflectionMethod $method) : TestRunnerInterface;
}
