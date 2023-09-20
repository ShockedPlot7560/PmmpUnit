<?php

namespace ShockedPlot7560\UnitTest\framework;

use React\Promise\PromiseInterface;
use function React\Promise\resolve;
use Webmozart\Assert\Assert;

class BaseAssert {
	protected function assertSame($expected, $actual, string $message = '') : PromiseInterface {
		Assert::same($expected, $actual, $message);

		return $this->assertSyncPromise();
	}

	private function assertSyncPromise() : PromiseInterface {
		return resolve(null);
	}
}
