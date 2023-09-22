<?php

namespace ShockedPlot7560\UnitTest\tests\normal\pocketmine;

use React\Promise\PromiseInterface;
use ShockedPlot7560\UnitTest\framework\TestCase;
use ShockedPlot7560\UnitTest\players\TestPlayer;

class PlayerPopupTest extends TestCase {
	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	public function testPlayerReceivePopupEquals() : PromiseInterface {
		return $this->getPlayer()
			->then(function (TestPlayer $player) {
				$promise = $this->promisePlayerReceivePopupEquals("Hello world!", $player);
				$player->getPlayer()->sendPopup("Hello world!");

				return $promise;
			});
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	public function testPlayerReceivePopupNotEquals() : PromiseInterface {
		return $this->getPlayer()
			->then(function (TestPlayer $player) {
				$promise = $this->promisePlayerReceivePopupNotEquals("Hello world!", $player);
				$player->getPlayer()->sendPopup("Hi world!");

				return $promise;
			});
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	public function testPlayerReceivePopupContains() : PromiseInterface {
		return $this->getPlayer()
			->then(function (TestPlayer $player) {
				$promise = $this->promisePlayerReceivePopupContains("Hello", $player);
				$player->getPlayer()->sendPopup("Hello world!");

				return $promise;
			});
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	public function testPlayerReceivePopupNotContains() : PromiseInterface {
		return $this->getPlayer()
			->then(function (TestPlayer $player) {
				$promise = $this->promisePlayerReceivePopupNotContains("Hello", $player);
				$player->getPlayer()->sendPopup("Hi world!");

				return $promise;
			});
	}
}
