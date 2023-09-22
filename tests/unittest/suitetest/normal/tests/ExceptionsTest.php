<?php

namespace unittest\suitetest\normal\tests;

use React\Promise\PromiseInterface;
use RuntimeException;
use ShockedPlot7560\UnitTest\framework\TestCase;

class ExceptionsTest extends TestCase {
	public function testExpectExceptionThrown() : PromiseInterface {
		$this->expectException(TestException::class);
		throw new TestException();
	}

	public function testExpectExceptionThrownWithMessage() : PromiseInterface {
		$this->expectExceptionMessage("Test");
		throw new TestException("Test");
	}

	public function testExpectExceptionThrownWithMessageMatches() : PromiseInterface {
		$this->expectExceptionMessageMatches("/Test/");
		throw new TestException("Test");
	}

	public function testExpectExceptionThrownWithMessageMatches2() : PromiseInterface {
		$this->expectExceptionMessageMatches("/Test/");
		throw new TestException("Test2");
	}

	public function testExpectExceptionThrownWithCode() : PromiseInterface {
		$this->expectExceptionCode(1);
		throw new TestException("Test", 1);
	}

	public function testExpectExceptionThrownWithCodeAndMessage() : PromiseInterface {
		$this->expectException(TestException::class);
		$this->expectExceptionMessage("Test");
		$this->expectExceptionCode(1);
		throw new TestException("Test", 1);
	}
}

class TestException extends RuntimeException {
}
