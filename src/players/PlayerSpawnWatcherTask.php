<?php

namespace ShockedPlot7560\PmmpUnit\players;

use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use RuntimeException;

class PlayerSpawnWatcherTask extends Task {
	public const MAX_TRYING = 100;

	/** @var Deferred<Player> */
	private Deferred $deferred;
	private int $trying = 0;

	public function __construct(
		private string $playerName
	) {
		$this->deferred = new Deferred();
	}

	/**
	 * @return PromiseInterface<Player>
	 */
	public function getPromise() : PromiseInterface {
		return $this->deferred->promise();
	}

	public function onRun() : void {
		$player = Server::getInstance()->getPlayerExact($this->playerName);

		if ($player !== null) {
			$this->deferred->resolve($player);

			$this->getHandler()?->cancel();
		}

		$this->trying++;

		if ($this->trying >= self::MAX_TRYING) {
			$this->deferred->reject(new RuntimeException("Player {$this->playerName} not found"));

			$this->getHandler()?->cancel();
		}
	}
}
