<?php

namespace ShockedPlot7560\PmmpUnit\tests\normal;

use React\Promise\PromiseInterface;
use RuntimeException;
use ShockedPlot7560\PmmpUnit\framework\attribute\ExpectedExceptionAttribute;
use ShockedPlot7560\PmmpUnit\framework\attribute\ExpectedExceptionCodeAttribute;
use ShockedPlot7560\PmmpUnit\framework\attribute\ExpectedExceptionMessageAttribute;
use ShockedPlot7560\PmmpUnit\framework\attribute\ExpectedExceptionMessageMatchesAttribute;
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
	#[ExpectedExceptionAttribute(TestException::class)]
	public function testExpectExceptionTrownWithAttribute() : PromiseInterface {
		throw new TestException();
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	#[ExpectedExceptionMessageAttribute("Test")]
	public function testExpectExceptionThrownWithMessageWithAttribute() : PromiseInterface {
		throw new TestException("Test");
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	#[ExpectedExceptionMessageMatchesAttribute("/Test/")]
	public function testExpectExceptionThrownWithMessageMatchesWithAttribute() : PromiseInterface {
		throw new TestException("Test");
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	#[ExpectedExceptionMessageMatchesAttribute("/Test/")]
	public function testExpectExceptionThrownWithMessageMatches2WithAttribute() : PromiseInterface {
		throw new TestException("Test2");
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	#[ExpectedExceptionCodeAttribute(1)]
	public function testExpectExceptionThrownWithCodeWithAttribute() : PromiseInterface {
		throw new TestException("Test", 1);
	}
}

class TestException extends RuntimeException {
}
