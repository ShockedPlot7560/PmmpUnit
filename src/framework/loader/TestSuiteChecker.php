<?php

namespace ShockedPlot7560\UnitTest\framework\loader;

use ReflectionClass;
use ShockedPlot7560\UnitTest\framework\loader\exception\ClassAbstractException;
use ShockedPlot7560\UnitTest\framework\loader\exception\ClassDoesntExtendTestCaseException;
use ShockedPlot7560\UnitTest\framework\TestCase;

class TestSuiteChecker {
	public static function check(ReflectionClass $class) : void {
		if (!$class->isSubclassOf(TestCase::class)) {
			throw new ClassDoesntExtendTestCaseException($class);
		}

		if ($class->isAbstract()) {
			throw new ClassAbstractException($class);
		}
	}
}
