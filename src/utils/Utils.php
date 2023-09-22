<?php

namespace ShockedPlot7560\UnitTest\utils;

use React\Promise\PromiseInterface;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ShockedPlot7560\UnitTest\framework\TestCase;

class Utils {
	/**
	 * @param ReflectionClass<TestCase> $class
	 * @return ReflectionMethod[]
	 */
	public static function getTestMethodsInTestCase(ReflectionClass $class) : array {
		$methodsByClass = [];
		foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
			$declaringClassName = $method->getDeclaringClass()->getName();
			// if ($method->getDeclaringClass()->getName() === Assert::class) {
			// 	continue;
			// }

			// if ($method->getDeclaringClass()->getName() === TestCase::class) {
			// 	continue;
			// }

			if (!isset($methodsByClass[$declaringClassName])) {
				$methodsByClass[$declaringClassName] = [];
			}

			$methodsByClass[$declaringClassName][] = $method;
		}

		$classNames = array_keys($methodsByClass);
		$classNames = array_reverse($classNames);

		$methods = [];

		foreach ($classNames as $className) {
			$methods = array_merge($methods, $methodsByClass[$className]);
		}

		return $methods;
	}

	public static function isTestMethod(ReflectionMethod $method) : bool {
		if (!$method->isPublic() || !str_starts_with($method->getName(), 'test') || !$method->hasReturnType()) {
			return false;
		}

		$returnType = $method->getReturnType();

		if (!$returnType instanceof ReflectionNamedType || $returnType->getName() !== PromiseInterface::class || $returnType->allowsNull()) {
			return false;
		}

		return true;
	}
}
