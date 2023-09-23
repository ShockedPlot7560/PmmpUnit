<?php

namespace ShockedPlot7560\PmmpUnit\framework\loader\exception;

use ReflectionClass;
use RuntimeException;
use ShockedPlot7560\PmmpUnit\framework\TestCase;

class ClassDoesntExtendTestCaseException extends RuntimeException {
	public function __construct(string|ReflectionClass $className, ?string $fileName = null) {
		if ($className instanceof ReflectionClass) {
			$fileName = $className->getFileName();
			$className = $className->getName();
		}
		parent::__construct(sprintf(
			'Class "%s" in file "%s" does not extend %s',
			$className,
			$fileName,
			TestCase::class
		));
	}
}
