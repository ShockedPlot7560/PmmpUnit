<?php

namespace ShockedPlot7560\PmmpUnit\utils;

use pocketmine\scheduler\AsyncTask;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;

class AsyncTaskDecorator extends AsyncTask {
    /**
     * @phpstan-param Deferred<mixed> $deferred
     */
	private function __construct(
		private AsyncTask $task,
		Deferred $deferred
	) {
		$this->storeLocal("deferred", $deferred);
	}

	/**
	 * Decorate an AsyncTask with a Deferred promise.
	 * Get your promise with {@see AsyncTaskDecorator::promise()}.
	 */
	public static function create(AsyncTask $task) : AsyncTaskDecorator {
		return new AsyncTaskDecorator($task, new Deferred());
	}

	public function onRun() : void {
		$this->task->onRun();
	}

	public function onCompletion() : void {
		$this->task->onCompletion();
		$this->getDeferred()->resolve($this->task->getResult());
	}

	public function onError() : void {
		$this->task->onError();
	}

	public function onProgressUpdate($progress) : void {
		$this->task->onProgressUpdate($progress);
	}

    /**
     * @phpstan-return PromiseInterface<mixed>
     */
	public function promise() : PromiseInterface {
		return $this->getDeferred()->promise();
	}

    /**
     * @phpstan-return Deferred<mixed>
     */
	private function getDeferred() : Deferred {
		$deferred = $this->fetchLocal("deferred");
		assert($deferred instanceof Deferred);

		return $deferred;
	}
}
