<?php

namespace ShockedPlot7560\PmmpUnit\framework\runner;

use React\Promise\PromiseInterface;
use ReflectionClass;
use ShockedPlot7560\PmmpUnit\framework\attribute\MethodAttributeToRunner;
use ShockedPlot7560\PmmpUnit\utils\Utils;

class SuiteTestRunner extends MultipleTestRunner implements TestRunnerInterface {
	public function __construct(
		private readonly string $name
	) {
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	public function run() : PromiseInterface {
		return TestRunnerUtils::runRec($this->getIterator());
	}

	public function __toString() : string {
		return "SuiteTest: " . $this->name;
	}

	public static function fromClassReflection(ReflectionClass $class) : self {
		$testSuite = new static($class->getName());

		foreach (Utils::getTestMethodsInTestCase($class) as $method) {
			foreach ($method->getAttributes() as $attribute) {
				$attribute = $attribute->newInstance();
				if ($attribute instanceof MethodAttributeToRunner) {
					$testSuite->addTest($attribute->getRunner($class, $method));

					continue 2;
				}
			}
			if (Utils::isTestMethod($method)) {
				$testSuite->addTest(new TestMethodRunner($class, $method));
			}
		}

		return $testSuite;
	}
}
