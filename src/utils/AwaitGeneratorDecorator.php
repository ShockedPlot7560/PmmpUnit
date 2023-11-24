<?php

namespace ShockedPlot7560\PmmpUnit\utils;

use Closure;
use DaveRandom\CallbackValidator\CallbackType;
use DaveRandom\CallbackValidator\ReturnType;
use Generator;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use SOFe\AwaitGenerator\Await;
use Throwable;

/**
 * @template T
 * @phpstan-implements PromiseInterface<T>
 */
class AwaitGeneratorDecorator implements PromiseInterface {
    /**
     * @phpstan-var Deferred<T>
     */
	private Deferred $delegate;

	public function __construct(
		Closure|Generator $generateGenerator
	) {
		$this->delegate = new Deferred();

		if ($generateGenerator instanceof Closure) {
			$type = new CallbackType(new ReturnType('Generator'));
			$type->isSatisfiedBy($generateGenerator);
		}

		try {
			Await::f2c(function () use ($generateGenerator) {
				if ($generateGenerator instanceof Closure) {
					$generateGenerator = $generateGenerator();
				}
				$result = yield from $generateGenerator;
				$this->delegate->resolve($result);
			});
		} catch (Throwable $e) {
			$this->delegate->reject($e);
		}
	}

	public function then(?callable $onFulfilled = null, ?callable $onRejected = null) : PromiseInterface {
		return $this->delegate->promise()->then($onFulfilled, $onRejected);
	}

	public function catch(callable $onRejected) : PromiseInterface {
		return $this->delegate->promise()->catch($onRejected);
	}

	public function finally(callable $onFulfilledOrRejected) : PromiseInterface {
		return $this->delegate->promise()->finally($onFulfilledOrRejected);
	}

	public function cancel() : void {
		$this->delegate->promise()->cancel();
	}

	public function otherwise(callable $onRejected) : PromiseInterface {
		return $this->delegate->promise()->otherwise($onRejected);
	}

	public function always(callable $onFulfilledOrRejected) : PromiseInterface {
		return $this->delegate->promise()->always($onFulfilledOrRejected);
	}
}
