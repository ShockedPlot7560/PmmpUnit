<?php

declare(strict_types=1);

namespace ShockedPlot7560\UnitTest\players\behaviour;

use ShockedPlot7560\UnitTest\players\TestPlayer;
use ShockedPlot7560\UnitTest\players\TestPlayerManager;

interface TestPlayerBehaviour {
	public static function init(TestPlayerManager $plugin) : void;

	/**
	 * @param mixed[] $data
	 * @return static
	 *
	 * @phpstan-param array<string, mixed> $data
	 */
	public static function create(array $data) : self;

	public function onAddToPlayer(TestPlayer $player) : void;

	public function onRemoveFromPlayer(TestPlayer $player) : void;

	public function tick(TestPlayer $player) : void;

	public function onRespawn(TestPlayer $player) : void;
}
