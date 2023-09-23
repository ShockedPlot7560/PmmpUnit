<?php

namespace ShockedPlot7560\PmmpUnit\framework\attribute;

use Attribute;
use Closure;

#[Attribute(Attribute::TARGET_METHOD)]
class DataProviderAttribute {
	public function __construct(
		private string|Closure $provider
	) {
	}

	public function getProvider() : string|Closure {
		return $this->provider;
	}
}
