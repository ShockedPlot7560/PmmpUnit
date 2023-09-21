<?php

namespace ShockedPlot7560\UnitTest\Utils;

use pocketmine\promise\Promise;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;

class PocketminePromiseDecorator implements PromiseInterface {
	private Deferred $deferred;

	public function __construct(
		private Promise $toDecorate
	) {
		$this->deferred = new Deferred();
		$this->toDecorate->onCompletion(function ($value) : void {
			$this->deferred->resolve($value);
		}, function ($reason) : void {
			$this->deferred->reject($reason);
		});
	}

	public function then(?callable $onFulfilled = null, ?callable $onRejected = null) : PromiseInterface {
		return $this->deferred->promise()->then($onFulfilled, $onRejected);
	}

	public function catch(callable $onRejected) : PromiseInterface {
		return $this->deferred->promise()->catch($onRejected);
	}

	public function finally(callable $onFulfilledOrRejected) : PromiseInterface {
		return $this->deferred->promise()->finally($onFulfilledOrRejected);
	}

	public function cancel() : void {
		$this->deferred->promise()->cancel();
	}

	public function otherwise(callable $onRejected) : PromiseInterface {
		return $this->deferred->promise()->otherwise($onRejected);
	}

	public function always(callable $onFulfilledOrRejected) : PromiseInterface {
		return $this->deferred->promise()->always($onFulfilledOrRejected);
	}
}
