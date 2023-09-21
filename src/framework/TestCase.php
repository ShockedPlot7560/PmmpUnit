<?php

namespace ShockedPlot7560\UnitTest\framework;

use pocketmine\lang\Translatable;
use pocketmine\network\mcpe\protocol\TextPacket;
use pocketmine\utils\TextFormat;
use React\Promise\PromiseInterface;
use function React\Promise\resolve;
use ShockedPlot7560\UnitTest\players\TestPlayer;
use ShockedPlot7560\UnitTest\UnitTest;

class TestCase extends BaseAssert {
	/** @var TestPlayer[] */
	private array $spawnedPlayers = [];

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

	protected function promisePlayerReceiveMessage(string|Translatable $message, TestPlayer $player, bool $cleanPacket = true) : PromiseInterface {
		$message = $this->translate($player, $message);

		return $player->registerSpecificSendPacketListener(TextPacket::class)
			->then(function (TextPacket $packet) use ($message, $cleanPacket) {
				if ($cleanPacket) {
					$format = TextFormat::clean($packet->message);
				} else {
					$format = $packet->message;
				}
				$message = TextFormat::clean($message);

				return $this->assertEquals($message, $format);
			});
	}

	protected function promisePlayerReceivePopup(string|Translatable $message, TestPlayer $player, bool $cleanPacket = true) : PromiseInterface {
		$message = $this->translate($player, $message);

		return $player->registerSpecificSendPacketListener(TextPacket::class)
			->then(function (TextPacket $packet) use ($message, $cleanPacket) {
				if ($cleanPacket) {
					$format = TextFormat::clean($packet->message);
				} else {
					$format = $packet->message;
				}
				$message = TextFormat::clean($message);

				return $this->assertEquals($message, $format);
			});
	}

	protected function promisePlayerReceiveMessageContains(string|Translatable $needle, TestPlayer $player, bool $cleanPacket = true) : PromiseInterface {
		$needle = $this->translate($player, $needle);

		return $player->registerSpecificSendPacketListener(TextPacket::class)
			->then(function (TextPacket $packet) use ($needle, $cleanPacket) {
				if ($cleanPacket) {
					$format = TextFormat::clean($packet->message);
				} else {
					$format = $packet->message;
				}
				$needle = TextFormat::clean($needle);

				return $this->assertStringContainsString($needle, $format);
			});
	}

	protected function promisePlayerNotReceiveMessage(string|Translatable $message, TestPlayer $player, bool $cleanPacket = true) : PromiseInterface {
		$message = $this->translate($player, $message);

		return $player->registerSpecificSendPacketListener(TextPacket::class)
			->then(function (TextPacket $packet) use ($message, $cleanPacket) {
				if ($cleanPacket) {
					$format = TextFormat::clean($packet->message);
				} else {
					$format = $packet->message;
				}
				$message = TextFormat::clean($message);

				return $this->assertNotEquals($message, $format);
			});
	}

	protected function promisePlayerNotReceiveMessageContains(string|Translatable $needle, TestPlayer $player, bool $cleanPacket = true) : PromiseInterface {
		$needle = $this->translate($player, $needle);

		return $player->registerSpecificSendPacketListener(TextPacket::class)
			->then(function (TextPacket $packet) use ($needle, $cleanPacket) {
				if ($cleanPacket) {
					$format = TextFormat::clean($packet->message);
				} else {
					$format = $packet->message;
				}
				$needle = TextFormat::clean($needle);

				return $this->assertStringNotContainsString($needle, $format);
			});
	}

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

		return $message;
	}
}
