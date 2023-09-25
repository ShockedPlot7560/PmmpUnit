<?php

namespace ShockedPlot7560\PmmpUnit\framework;

use Iterator;
use React\Promise\PromiseInterface;
use function React\Promise\resolve;
use ShockedPlot7560\PmmpUnit\framework\result\TestResults;
use Throwable;
use Webmozart\Assert\InvalidArgumentException;

trait MultipleTestRunner {
	/**
	 * @param Iterator<RunnableTest> $iterator
	 * @phpstan-return PromiseInterface<null>
	 */
	private function runRec(Iterator $iterator) : PromiseInterface {
		if ($iterator->valid()) {
			$test = $iterator->current();
			TestMemory::$currentTest = $test;
			$iterator->next();

			$promise = null;
			try {
				$promise = $test->run()
					->then(function () use ($test) {
						TestResults::successTest($test);
					}, function (Throwable $throwable) use ($test) {
						return $this->failed($test, $throwable);
					});
			} catch (InvalidArgumentException $assertFailed) {
				$promise = $this->failed($test, $assertFailed);
			} finally {
				return $promise?->then(function () use ($iterator) {
					TestMemory::$currentTest = null;

					return $this->runRec($iterator);
				}) ?? resolve(null);
			}
		} else {
			return resolve(null);
		}
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	private function failed(RunnableTest $test, Throwable $throwable) : PromiseInterface {
		TestResults::failedTest($test, $throwable);

		return resolve(null);
	}
}
