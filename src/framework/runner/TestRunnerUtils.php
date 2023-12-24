<?php

namespace ShockedPlot7560\PmmpUnit\framework\runner;

use Iterator;
use React\Promise\PromiseInterface;
use function React\Promise\resolve;
use ShockedPlot7560\PmmpUnit\framework\event\TestEndEvent;
use ShockedPlot7560\PmmpUnit\framework\event\TestFailedEvent;
use ShockedPlot7560\PmmpUnit\framework\event\TestStartEvent;
use ShockedPlot7560\PmmpUnit\framework\event\TestSuccessEvent;
use Throwable;
use Webmozart\Assert\InvalidArgumentException;

class TestRunnerUtils {
	/**
	 * @param Iterator<TestRunnerInterface> $iterator
	 * @phpstan-return PromiseInterface<null>
	 */
	public static function runRec(Iterator $iterator) : PromiseInterface {
		if ($iterator->valid()) {
			$test = $iterator->current();
			(new TestStartEvent($test))->call();
			$iterator->next();

			try {
				$promise = $test->run()
					->then(function () use ($test) {
						(new TestSuccessEvent($test))->call();
					}, function (Throwable $throwable) use ($test) {
						return self::failed($test, $throwable);
					});
			} catch (InvalidArgumentException $assertFailed) {
				$promise = self::failed($test, $assertFailed);
			} finally {
				return $promise->then(function () use ($iterator, $test) {
					(new TestEndEvent($test))->call();

					return self::runRec($iterator);
				});
			}
		} else {
			return resolve(null);
		}
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	private static function failed(TestRunnerInterface $test, Throwable $throwable) : PromiseInterface {
		(new TestFailedEvent($test, $throwable))->call();

		return resolve(null);
	}
}
