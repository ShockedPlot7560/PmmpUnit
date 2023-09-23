<?php

namespace ShockedPlot7560\PmmpUnit\framework\loader\exception;

class ClassDoesntExistException extends LoaderException {
	public function __construct(string $className, string $fileName) {
		parent::__construct(sprintf(
			'Class "%s" does not exist in "%s".',
			$className,
			$fileName
		));
	}
}
