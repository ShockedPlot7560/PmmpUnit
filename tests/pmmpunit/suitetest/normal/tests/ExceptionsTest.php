<?php

namespace ShockedPlot7560\PmmpUnit\tests\normal;

use React\Promise\PromiseInterface;
use RuntimeException;
use ShockedPlot7560\PmmpUnit\framework\TestCase;

class ExceptionsTest extends TestCase {
	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	public function testExpectExceptionThrown() : PromiseInterface {
		$this->expectException(TestException::class);
		throw new TestException();
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	public function testExpectExceptionThrownWithMessage() : PromiseInterface {
		$this->expectExceptionMessage("Test");
		throw new TestException("Test");
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	public function testExpectExceptionThrownWithMessageMatches() : PromiseInterface {
		$this->expectExceptionMessageMatches("/Test/");
		throw new TestException("Test");
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	public function testExpectExceptionThrownWithMessageMatches2() : PromiseInterface {
		$this->expectExceptionMessageMatches("/Test/");
		throw new TestException("Test2");
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	public function testExpectExceptionThrownWithCode() : PromiseInterface {
		$this->expectExceptionCode(1);
		throw new TestException("Test", 1);
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	public function testExpectExceptionThrownWithCodeAndMessage() : PromiseInterface {
		$this->expectException(TestException::class);
		$this->expectExceptionMessage("Test");
		$this->expectExceptionCode(1);
		throw new TestException("Test", 1);
	}
}

class TestException extends RuntimeException {
}
