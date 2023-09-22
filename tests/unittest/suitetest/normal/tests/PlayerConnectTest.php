<?php

namespace unittest\suitetest\normal\tests;

use pocketmine\Server;
use React\Promise\PromiseInterface;
use ShockedPlot7560\UnitTest\framework\TestCase;
use ShockedPlot7560\UnitTest\players\TestPlayer;

class PlayerConnectTest extends TestCase {
	/**
	 * @return PromiseInterface<null>
	 */
	public function testPlayerConnected() : PromiseInterface {
		return $this->getPlayer()
			->then(function (TestPlayer $player) {
				return $this->assertNotNull(Server::getInstance()->getPlayerExact($player->getPlayer()->getName()));
			});
	}
}
