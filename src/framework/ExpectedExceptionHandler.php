<?php

namespace ShockedPlot7560\PmmpUnit\framework;

use ReflectionMethod;
use ShockedPlot7560\PmmpUnit\framework\attribute\ExpectedExceptionAttribute;
use ShockedPlot7560\PmmpUnit\framework\attribute\ExpectedExceptionCodeAttribute;
use ShockedPlot7560\PmmpUnit\framework\attribute\ExpectedExceptionMessageAttribute;
use ShockedPlot7560\PmmpUnit\framework\attribute\ExpectedExceptionMessageMatchesAttribute;
use Throwable;
use Webmozart\Assert\Assert;

class ExpectedExceptionHandler {
	/** @var class-string<Throwable>|null */
	private ?string $expectedException = null;
	private ?string $expectedExceptionMessage = null;
	private ?string $expectedExceptionMessageRegExp = null;
	private null|int|string $expectedExceptionCode = null;

	public function __construct(
		private ReflectionMethod $method,
	) {
		$attributes = $this->method->getAttributes();
		foreach ($attributes as $attribute) {
			$attribute = $attribute->newInstance();
			if ($attribute instanceof ExpectedExceptionAttribute) {
				$this->expectException($attribute->exception);
			}

			if ($attribute instanceof ExpectedExceptionCodeAttribute) {
				$this->expectExceptionCode($attribute->code);
			}

			if ($attribute instanceof ExpectedExceptionMessageAttribute) {
				$this->expectExceptionMessage($attribute->message);
			}

			if ($attribute instanceof ExpectedExceptionMessageMatchesAttribute) {
				$this->expectExceptionMessageMatches($attribute->regex);
			}
		}
	}

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

	public function expectedExceptionWasNotRaised() : void {
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

	public function shouldExceptionExpectationsBeVerified(Throwable $throwable) : bool {
		return $this->expectedException !== null
			|| $this->expectedExceptionCode !== null
			|| $this->expectedExceptionMessage !== null
			|| $this->expectedExceptionMessageRegExp !== null;
	}

	public function verifyExceptionExpectations(Throwable $throwable) : void {
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
