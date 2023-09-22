<?php

namespace unittest\suitetest\normal\tests\pocketmine;

use React\Promise\PromiseInterface;
use ShockedPlot7560\UnitTest\framework\TestCase;
use ShockedPlot7560\UnitTest\players\TestPlayer;

class PlayerPopupTest extends TestCase {
	public function testPlayerReceivePopupEquals() : PromiseInterface {
		return $this->getPlayer()
			->then(function (TestPlayer $player) {
				$promise = $this->promisePlayerReceivePopupEquals("Hello world!", $player);
				$player->getPlayer()->sendPopup("Hello world!");

				return $promise;
			});
	}

	public function testPlayerReceivePopupNotEquals() : PromiseInterface {
		return $this->getPlayer()
			->then(function (TestPlayer $player) {
				$promise = $this->promisePlayerReceivePopupNotEquals("Hello world!", $player);
				$player->getPlayer()->sendPopup("Hi world!");

				return $promise;
			});
	}

	public function testPlayerReceivePopupContains() : PromiseInterface {
		return $this->getPlayer()
			->then(function (TestPlayer $player) {
				$promise = $this->promisePlayerReceivePopupContains("Hello", $player);
				$player->getPlayer()->sendPopup("Hello world!");

				return $promise;
			});
	}

	public function testPlayerReceivePopupNotContains() : PromiseInterface {
		return $this->getPlayer()
			->then(function (TestPlayer $player) {
				$promise = $this->promisePlayerReceivePopupNotContains("Hello", $player);
				$player->getPlayer()->sendPopup("Hi world!");

				return $promise;
			});
	}
}
