<?php

namespace ShockedPlot7560\PmmpUnit\framework\assert;

use pocketmine\network\mcpe\protocol\TextPacket;
use React\Promise\PromiseInterface;
use ShockedPlot7560\PmmpUnit\players\TestPlayer;

trait PlayerMessageAssert {
	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	protected function promisePlayerReceiveMessageEquals(string $message, TestPlayer $player, bool $cleanPacket = true) : PromiseInterface {
		$m = $this->translate($player, $message);

		return $this->promisePlayerReceiveMessage($player, $cleanPacket)
			->then(function (string $message) use ($m) {
				return $this->assertEquals($m, $message);
			});
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	protected function promisePlayerReceiveMessageNotEquals(string $message, TestPlayer $player, bool $cleanPacket = true) : PromiseInterface {
		$m = $this->translate($player, $message);

		return $this->promisePlayerReceiveMessage($player, $cleanPacket)
			->then(function (string $message) use ($m) {
				return $this->assertNotEquals($m, $message);
			});
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	protected function promisePlayerReceiveMessageContains(string $needle, TestPlayer $player, bool $cleanPacket = true) : PromiseInterface {
		$n = $this->translate($player, $needle);

		return $this->promisePlayerReceiveMessage($player, $cleanPacket)
			->then(function (string $message) use ($n) {
				return $this->assertStringContainsString($n, $message);
			});
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	protected function promisePlayerReceiveMessageNotContains(string $needle, TestPlayer $player, bool $cleanPacket = true) : PromiseInterface {
		$n = $this->translate($player, $needle);

		return $this->promisePlayerReceiveMessage($player, $cleanPacket)
			->then(function (string $message) use ($n) {
				return $this->assertStringNotContainsString($n, $message);
			});
	}

	/**
	 * @phpstan-return PromiseInterface<string>
	 */
	private function promisePlayerReceiveMessage(TestPlayer $player, bool $cleanPacket = true) : PromiseInterface {
		// if ($message instanceof Translatable) {
		// 	return $this->promisePlayerReceiveTextPacket($player, TextPacket::TYPE_TRANSLATION, $cleanPacket)
		// 		->then(function (TextPacket $packet) use ($player) : string {
		// 			var_dump($packet);

		// 			return $packet->parameters[1];
		// 		});
		// } else {
		return $this->promisePlayerReceiveTextPacket($player, TextPacket::TYPE_RAW)
			->then(function (TextPacket $packet) : string {
				return $packet->message;
			});
		// }
	}
}
