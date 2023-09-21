<?php

namespace ShockedPlot7560\UnitTest\framework;

use React\Promise\PromiseInterface;
use function React\Promise\resolve;
use ShockedPlot7560\UnitTest\UnitTest;

class TestCase extends BaseAssert {
	use PocketmineSpecificAssert;

	public function setUp() : PromiseInterface {
		return resolve(null);
	}

	public function tearDown() : PromiseInterface {
		foreach ($this->spawnedPlayers as $player) {
			UnitTest::getInstance()->getTestPlayerManager()->removePlayer($player->getPlayer());
		}

		return resolve(null);
	}

	public function onLoad() : void {
	}

	public function onEnable() : void {
	}

	public function onDisable() : void {
	}
}
