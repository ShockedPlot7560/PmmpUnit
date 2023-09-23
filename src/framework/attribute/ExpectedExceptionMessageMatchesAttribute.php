<?php

namespace ShockedPlot7560\PmmpUnit\framework\attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class ExpectedExceptionMessageMatchesAttribute {
	public function __construct(
		public readonly string $regex
	) {
	}
}
