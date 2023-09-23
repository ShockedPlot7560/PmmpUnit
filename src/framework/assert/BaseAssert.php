<?php

namespace ShockedPlot7560\PmmpUnit\framework\assert;

use Countable;
use React\Promise\PromiseInterface;
use function React\Promise\resolve;
use Webmozart\Assert\Assert;

class BaseAssert {
	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	protected function assertSame(mixed $expected, mixed $actual, string $message = '') : PromiseInterface {
		Assert::same($expected, $actual, $message);

		return $this->assertSyncPromise();
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	protected function assertNotSame(mixed $expected, mixed $actual, string $message = '') : PromiseInterface {
		Assert::notSame($expected, $actual, $message);

		return $this->assertSyncPromise();
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	protected function assertEquals(mixed $expected, mixed $actual, string $message = '') : PromiseInterface {
		Assert::eq($expected, $actual, $message);

		return $this->assertSyncPromise();
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	protected function assertNotEquals(mixed $expected, mixed $actual, string $message = '') : PromiseInterface {
		Assert::notEq($expected, $actual, $message);

		return $this->assertSyncPromise();
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	protected function assertStringContainsString(string $needle, string $haystack, string $message = '') : PromiseInterface {
		Assert::contains($haystack, $needle, $message);

		return $this->assertSyncPromise();
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	protected function assertStringNotContainsString(string $needle, string $haystack, string $message = '') : PromiseInterface {
		Assert::notContains($haystack, $needle, $message);

		return $this->assertSyncPromise();
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	protected function assertInstanceOf(mixed $expected, string|object $actual, string $message = '') : PromiseInterface {
		Assert::isInstanceOf($actual, $expected, $message);

		return $this->assertSyncPromise();
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	protected function assertNotInstanceOf(mixed $expected, string|object $actual, string $message = '') : PromiseInterface {
		Assert::notInstanceOf($actual, $expected, $message);

		return $this->assertSyncPromise();
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	protected function assertEmpty(mixed $actual, string $message = '') : PromiseInterface {
		Assert::isEmpty($actual, $message);

		return $this->assertSyncPromise();
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	protected function assertNotEmpty(mixed $actual, string $message = '') : PromiseInterface {
		Assert::notEmpty($actual, $message);

		return $this->assertSyncPromise();
	}

	/**
	 * @param mixed[]|Countable $actual
	 * @phpstan-return PromiseInterface<null>
	 */
	protected function assertCount(int $expected, array|Countable $actual, string $message = '') : PromiseInterface {
		Assert::count($actual, $expected, $message);

		return $this->assertSyncPromise();
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	protected function assertTrue(bool $actual, string $message = '') : PromiseInterface {
		Assert::true($actual, $message);

		return $this->assertSyncPromise();
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	protected function assertFalse(bool $actual, string $message = '') : PromiseInterface {
		Assert::false($actual, $message);

		return $this->assertSyncPromise();
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	protected function assertNull(mixed $actual, string $message = '') : PromiseInterface {
		Assert::null($actual, $message);

		return $this->assertSyncPromise();
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	protected function assertNotNull(mixed $actual, string $message = '') : PromiseInterface {
		Assert::notNull($actual, $message);

		return $this->assertSyncPromise();
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	private function assertSyncPromise() : PromiseInterface {
		return resolve(null);
	}
}
