<?php

namespace ShockedPlot7560\UnitTest\players;

use AssertionError;
use pocketmine\player\Player;
use React\Promise\PromiseInterface;
use ShockedPlot7560\UnitTest\players\info\TestPlayerInfoBuilder;
use ShockedPlot7560\UnitTest\UnitTest;
use ShockedPlot7560\UnitTest\Utils\PocketminePromiseDecorator;

class PlayerBag {
	/**
	 * @return PromiseInterface<TestPlayer>
	 */
	public function shift() : PromiseInterface {
		return $this->create();
	}

	/**
	 * @return PromiseInterface<TestPlayer>
	 */
	private function create() : PromiseInterface {
		$fakePlayer = UnitTest::getInstance()->getTestPlayerManager();

		$info = TestPlayerInfoBuilder::create()->build();

		return (new PocketminePromiseDecorator($fakePlayer->addPlayer($info)))
			->then(function () use ($info) : PromiseInterface {
				$task = new PlayerSpawnWatcherTask($info->username);
				UnitTest::getInstance()->getScheduler()->scheduleDelayedRepeatingTask($task, 1, 1);

				return $task->getPromise()
					->then(function (Player $player) {
						return UnitTest::getInstance()->getTestPlayerManager()->getTestPlayer($player) ?? throw new AssertionError("Player is null");
					});
			});
	}
}
