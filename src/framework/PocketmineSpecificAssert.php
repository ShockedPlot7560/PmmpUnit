<?php

namespace ShockedPlot7560\UnitTest\framework;

use pocketmine\lang\Translatable;
use pocketmine\network\mcpe\protocol\TextPacket;
use pocketmine\utils\TextFormat;
use React\Promise\PromiseInterface;
use ShockedPlot7560\UnitTest\players\TestPlayer;
use ShockedPlot7560\UnitTest\UnitTest;

trait PocketmineSpecificAssert {
	/** @var TestPlayer[] */
	private array $spawnedPlayers = [];

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	protected function promisePlayerReceiveMessageEquals(string|Translatable $message, TestPlayer $player, bool $cleanPacket = true) : PromiseInterface {
		$message = $this->translate($player, $message);

		return $this->promisePlayerReceiveMessage($player, $cleanPacket)
			->then(function (string $format) use ($message) {
				return $this->assertEquals($message, $format);
			});
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	protected function promisePlayerReceiveMessageNotEquals(string|Translatable $message, TestPlayer $player, bool $cleanPacket = true) : PromiseInterface {
		$message = $this->translate($player, $message);

		return $this->promisePlayerReceiveMessage($player, $cleanPacket)
			->then(function (string $format) use ($message) {
				return $this->assertNotEquals($message, $format);
			});
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	protected function promisePlayerReceiveMessageContains(string|Translatable $needle, TestPlayer $player, bool $cleanPacket = true) : PromiseInterface {
		$needle = $this->translate($player, $needle);

		return $this->promisePlayerReceiveMessage($player, $cleanPacket)
			->then(function (string $format) use ($needle) {
				return $this->assertStringContainsString($needle, $format);
			});
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	protected function promisePlayerReceiveMessageNotContains(string|Translatable $needle, TestPlayer $player, bool $cleanPacket = true) : PromiseInterface {
		$needle = $this->translate($player, $needle);

		return $this->promisePlayerReceiveMessage($player, $cleanPacket)
			->then(function (string $format) use ($needle) {
				return $this->assertStringNotContainsString($needle, $format);
			});
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
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

	/**
	 * @phpstan-return PromiseInterface<string>
	 */
	private function promisePlayerReceiveMessage(TestPlayer $player, bool $cleanPacket = true) : PromiseInterface {
		return $player->registerSpecificSendPacketListener(TextPacket::class)
			->then(function (TextPacket $packet) use ($cleanPacket) : string {
				if ($cleanPacket) {
					$format = TextFormat::clean($packet->message);
				} else {
					$format = $packet->message;
				}

				return $format;
			});
	}

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
