<?php

namespace ShockedPlot7560\PmmpUnit\framework;

use Throwable;
use Webmozart\Assert\Assert;

trait ExceptionExpectationTrait {
	/** @var class-string<Throwable>|null */
	private ?string $expectedException = null;
	private ?string $expectedExceptionMessage = null;
	private ?string $expectedExceptionMessageRegExp = null;
	private null|int|string $expectedExceptionCode = null;

	/**
	 * @phpstan-param class-string<Throwable> $exception
	 */
	final public function expectException(string $exception) : void {
		$this->expectedException = $exception;
	}

	final public function expectExceptionCode(int|string $code) : void {
		$this->expectedExceptionCode = $code;
	}

	final public function expectExceptionMessage(string $message) : void {
		$this->expectedExceptionMessage = $message;
	}

	final public function expectExceptionMessageMatches(string $regularExpression) : void {
		$this->expectedExceptionMessageRegExp = $regularExpression;
	}

	private function expectedExceptionWasNotRaised() : void {
		if ($this->expectedException !== null) {
			Assert::null($this->expectedException, "Failed asserting that exception of type {$this->expectedException} is thrown");
		}
		if ($this->expectedExceptionCode !== null) {
			Assert::null($this->expectedExceptionCode, "Failed asserting that exception with code {$this->expectedExceptionCode} is thrown");
		}
		if ($this->expectedExceptionMessage !== null) {
			Assert::null($this->expectedExceptionMessage, "Failed asserting that exception with message {$this->expectedExceptionMessage} is thrown");
		}
		if ($this->expectedExceptionMessageRegExp !== null) {
			Assert::null($this->expectedExceptionMessageRegExp, "Failed asserting that exception with message {$this->expectedExceptionMessageRegExp} is thrown");
		}
	}

	private function shouldExceptionExpectationsBeVerified(Throwable $throwable) : bool {
		return $this->expectedException !== null
			|| $this->expectedExceptionCode !== null
			|| $this->expectedExceptionMessage !== null
			|| $this->expectedExceptionMessageRegExp !== null;
	}

	private function verifyExceptionExpectations(Throwable $throwable) : void {
		if ($this->expectedException !== null) {
			Assert::isInstanceOf(
				$throwable,
				$this->expectedException,
				"Failed asserting that exception of type {$this->expectedException} is thrown"
			);
		}

		if ($this->expectedExceptionCode !== null) {
			Assert::eq(
				$throwable->getCode(),
				$this->expectedExceptionCode,
				"Failed asserting that exception with code {$this->expectedExceptionCode} is thrown"
			);
		}

		if ($this->expectedExceptionMessage !== null) {
			Assert::eq(
				$throwable->getMessage(),
				$this->expectedExceptionMessage,
				"Failed asserting that exception with message {$this->expectedExceptionMessage} is thrown"
			);
		}

		if ($this->expectedExceptionMessageRegExp !== null) {
			Assert::regex(
				$throwable->getMessage(),
				$this->expectedExceptionMessageRegExp,
				"Failed asserting that exception with message {$this->expectedExceptionMessageRegExp} is thrown"
			);
		}
	}
}
