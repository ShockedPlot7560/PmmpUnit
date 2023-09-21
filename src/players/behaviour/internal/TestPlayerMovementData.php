<?php

declare(strict_types=1);

namespace ShockedPlot7560\UnitTest\players\behaviour\internal;

use pocketmine\math\Vector3;

final class TestPlayerMovementData{

	public static function new() : self{
		return new self(Vector3::zero());
	}

	public function __construct(
		public Vector3 $motion
	){}
}