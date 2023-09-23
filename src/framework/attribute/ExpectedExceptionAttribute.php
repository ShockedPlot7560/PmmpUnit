<?php

namespace ShockedPlot7560\PmmpUnit\framework\attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class ExpectedExceptionAttribute {
	/**
	 * @phpstan-param class-string<\Throwable> $exception
	 */
	public function __construct(
		public readonly string $exception
	) {
	}
}
