<?php

namespace ShockedPlot7560\PmmpUnit\framework\attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class DataProviderAttribute {
	public function __construct(
		private string $provider
	) {
	}

	public function getProvider() : string {
		return $this->provider;
	}
}
