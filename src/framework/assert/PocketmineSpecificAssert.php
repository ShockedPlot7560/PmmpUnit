<?php

namespace ShockedPlot7560\PmmpUnit\framework\assert;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use React\Promise\PromiseInterface;
use ShockedPlot7560\PmmpUnit\players\TestPlayer;
use ShockedPlot7560\PmmpUnit\PmmpUnit;
use ShockedPlot7560\PmmpUnit\utils\AsyncTaskDecorator;

trait PocketmineSpecificAssert {
	use PlayerTextPacketAssert;

	/** @var TestPlayer[] */
	private array $spawnedPlayers = [];

	/**
	 * @phpstan-return PromiseInterface<TestPlayer>
	 */
	protected function getPlayer() : PromiseInterface {
		return PmmpUnit::getInstance()->getPlayerBag()->shift()
			->then(function (TestPlayer $player) : TestPlayer {
				$this->spawnedPlayers[] = $player;

				return $player;
			});
	}

    /**
     * @phpstan-return PromiseInterface<mixed>
     */
	protected function submitAsyncTask(AsyncTask $task) : PromiseInterface {
		$decorator = AsyncTaskDecorator::create($task);
		Server::getInstance()->getAsyncPool()->submitTask($decorator);

		return $decorator->promise();
	}

    /**
     * @phpstan-return PromiseInterface<mixed>
     */
	protected function submitAsyncTaskToWorker(AsyncTask $task, int $workedId) : PromiseInterface {
		$decorator = AsyncTaskDecorator::create($task);
		Server::getInstance()->getAsyncPool()->submitTaskToWorker($decorator, $workedId);

		return $decorator->promise();
	}
}
