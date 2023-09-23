<?php

namespace ShockedPlot7560\PmmpUnit\framework\attribute\dataProvider;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class DataProviderAttribute {
	public function __construct(
		public readonly string $provider
	) {
	}
}
