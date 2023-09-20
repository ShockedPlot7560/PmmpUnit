<?php

namespace ShockedPlot7560\UnitTest\framework\loader\exception;

use RuntimeException;

class ClassDoesntExistException extends RuntimeException {
	public function __construct(string $className, string $fileName) {
		parent::__construct(sprintf(
			'Class "%s" does not exist in "%s".',
			$className,
			$fileName
		));
	}
}
