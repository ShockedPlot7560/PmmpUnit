<?php

declare(strict_types=1);

namespace ShockedPlot7560\PmmpUnit\players\behaviour\internal;

use RuntimeException;

trait InternalTestPlayerBehaviourTrait {
	public static function create(array $data) : self {
		throw new RuntimeException("Cannot create internal test player behavior " . static::class . " from data");
	}
}
