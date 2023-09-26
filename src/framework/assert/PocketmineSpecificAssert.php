<?php

namespace ShockedPlot7560\PmmpUnit\framework\assert;

use React\Promise\PromiseInterface;
use ShockedPlot7560\PmmpUnit\players\TestPlayer;
use ShockedPlot7560\PmmpUnit\PmmpUnit;

trait PocketmineSpecificAssert {
	use PlayerTextPacketAssert;

	/** @var TestPlayer[] */
	private array $spawnedPlayers = [];

	/**
	 * @phpstan-return PromiseInterface<TestPlayer>
	 */
	protected function getPlayer() : PromiseInterface {
		return PmmpUnit::getInstance()->getPlayerBag()->shift()
			->then(function (TestPlayer $player) : TestPlayer {
				$this->spawnedPlayers[] = $player;

				return $player;
			});
	}
}
