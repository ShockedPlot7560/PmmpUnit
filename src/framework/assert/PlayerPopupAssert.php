<?php

namespace ShockedPlot7560\UnitTest\framework\assert;

use pocketmine\network\mcpe\protocol\TextPacket;
use React\Promise\PromiseInterface;
use ShockedPlot7560\UnitTest\players\TestPlayer;

trait PlayerPopupAssert {
	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	protected function promisePlayerReceivePopupEquals(string $message, TestPlayer $player, bool $cleanPacket = true) : PromiseInterface {
		$message = $this->translate($player, $message);

		return $this->promisePlayerReceivePopup($player, $cleanPacket)
			->then(function (string $format) use ($message) {
				return $this->assertEquals($message, $format);
			});
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	protected function promisePlayerReceivePopupNotEquals(string $message, TestPlayer $player, bool $cleanPacket = true) : PromiseInterface {
		$message = $this->translate($player, $message);

		return $this->promisePlayerReceivePopup($player, $cleanPacket)
			->then(function (string $format) use ($message) {
				return $this->assertNotEquals($message, $format);
			});
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	protected function promisePlayerReceivePopupContains(string $needle, TestPlayer $player, bool $cleanPacket = true) : PromiseInterface {
		$needle = $this->translate($player, $needle);

		return $this->promisePlayerReceivePopup($player, $cleanPacket)
			->then(function (string $format) use ($needle) {
				return $this->assertStringContainsString($needle, $format);
			});
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	protected function promisePlayerReceivePopupNotContains(string $needle, TestPlayer $player, bool $cleanPacket = true) : PromiseInterface {
		$needle = $this->translate($player, $needle);

		return $this->promisePlayerReceivePopup($player, $cleanPacket)
			->then(function (string $format) use ($needle) {
				return $this->assertStringNotContainsString($needle, $format);
			});
	}

	/**
	 * @phpstan-return PromiseInterface<string>
	 */
	private function promisePlayerReceivePopup(TestPlayer $player, bool $cleanPacket = true) : PromiseInterface {
		return $this->promisePlayerReceiveTextPacket($player, TextPacket::TYPE_POPUP, $cleanPacket)
			->then(function (TextPacket $packet) {
				return $packet->message;
			});
	}
}
