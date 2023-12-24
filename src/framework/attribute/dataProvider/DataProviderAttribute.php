<?php

namespace ShockedPlot7560\PmmpUnit\framework\attribute\dataProvider;

use Attribute;
use ReflectionClass;
use ReflectionMethod;
use ShockedPlot7560\PmmpUnit\framework\attribute\MethodAttributeToRunner;
use ShockedPlot7560\PmmpUnit\framework\runner\DataProviderRunner;
use ShockedPlot7560\PmmpUnit\framework\runner\TestRunnerInterface;

#[Attribute(Attribute::TARGET_METHOD)]
class DataProviderAttribute implements MethodAttributeToRunner {
	public function __construct(
		public readonly string $provider
	) {
	}

	public function getRunner(ReflectionClass $class, ReflectionMethod $method) : TestRunnerInterface {
		return new DataProviderRunner($class, $method, $this);
	}
}
