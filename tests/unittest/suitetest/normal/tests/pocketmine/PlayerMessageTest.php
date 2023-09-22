<?php

namespace unittest\suitetest\normal\tests\pocketmine;

use React\Promise\PromiseInterface;
use ShockedPlot7560\UnitTest\framework\TestCase;
use ShockedPlot7560\UnitTest\players\TestPlayer;

class PlayerMessageTest extends TestCase {
	public function testPlayerReceiveMessageEqualsString() : PromiseInterface {
		return $this->getPlayer()
			->then(function (TestPlayer $player) {
				$promise = $this->promisePlayerReceiveMessageEquals("Hello world!", $player);
				$player->getPlayer()->sendMessage("Hello world!");

				return $promise;
			});
	}

	public function testPlayerReceiveMessageNotEqualsString() : PromiseInterface {
		return $this->getPlayer()
			->then(function (TestPlayer $player) {
				$promise = $this->promisePlayerReceiveMessageNotEquals("Hello world!", $player);
				$player->getPlayer()->sendMessage("Hi world!");

				return $promise;
			});
	}

	public function testPlayerReceiveMessageContainsString() : PromiseInterface {
		return $this->getPlayer()
			->then(function (TestPlayer $player) {
				$promise = $this->promisePlayerReceiveMessageContains("Hello", $player);
				$player->getPlayer()->sendMessage("Hello world!");

				return $promise;
			});
	}

	public function testPlayerReceiveMessageNotContainsString() : PromiseInterface {
		return $this->getPlayer()
			->then(function (TestPlayer $player) {
				$promise = $this->promisePlayerReceiveMessageNotContains("Hello", $player);
				$player->getPlayer()->sendMessage("Hi world!");

				return $promise;
			});
	}
}
