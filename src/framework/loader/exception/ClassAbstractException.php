<?php

namespace ShockedPlot7560\PmmpUnit\framework\loader\exception;

use ReflectionClass;
use RuntimeException;

class ClassAbstractException extends RuntimeException {
	public function __construct(string|ReflectionClass $className, ?string $file = null) {
		if ($className instanceof ReflectionClass) {
			$file = $className->getFileName();
			$className = $className->getName();
		}
		parent::__construct(
			sprintf(
				'Class %s declared in %s is abstract',
				$className,
				$file,
			),
		);
	}
}
