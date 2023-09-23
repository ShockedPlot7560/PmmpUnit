<?php

namespace ShockedPlot7560\PmmpUnit\framework\loader;

use ReflectionClass;
use ShockedPlot7560\PmmpUnit\framework\loader\exception\ClassAbstractException;
use ShockedPlot7560\PmmpUnit\framework\loader\exception\ClassDoesntExtendTestCaseException;
use ShockedPlot7560\PmmpUnit\framework\TestCase;

class TestSuiteChecker {
	/**
	 * @param ReflectionClass<TestCase> $class
	 */
	public static function check(ReflectionClass $class) : void {
		if (!$class->isSubclassOf(TestCase::class)) {
			throw new ClassDoesntExtendTestCaseException($class);
		}

		if ($class->isAbstract()) {
			throw new ClassAbstractException($class);
		}
	}
}
