<?php

namespace ShockedPlot7560\UnitTest\framework\assert;

use pocketmine\lang\Translatable;
use pocketmine\utils\TextFormat;
use React\Promise\PromiseInterface;
use ShockedPlot7560\UnitTest\players\TestPlayer;
use ShockedPlot7560\UnitTest\UnitTest;

trait PocketmineSpecificAssert {
	use PlayerTextPacketAssert;

	/** @var TestPlayer[] */
	private array $spawnedPlayers = [];

	/**
	 * @phpstan-return PromiseInterface<TestPlayer>
	 */
	protected function getPlayer() : PromiseInterface {
		return UnitTest::getInstance()->getPlayerBag()->shift()
			->then(function (TestPlayer $player) : TestPlayer {
				$this->spawnedPlayers[] = $player;

				return $player;
			});
	}

	protected function translate(TestPlayer $player, string|Translatable $message) : string {
		if ($message instanceof Translatable) {
			$message = $player->getPlayer()->getLanguage()->translate($message);
		}

		return TextFormat::clean($message);
	}
}
