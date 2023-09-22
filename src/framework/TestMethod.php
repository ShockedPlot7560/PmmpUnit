<?php

namespace ShockedPlot7560\UnitTest\framework;

use React\Promise\PromiseInterface;
use ReflectionClass;
use ReflectionMethod;
use Throwable;
use Webmozart\Assert\Assert;

class TestMethod implements RunnableTest {
	/** @var class-string<Throwable>|null */
	private ?string $expectedException = null;
	private ?string $expectedExceptionMessage = null;
	private ?string $expectedExceptionMessageRegExp = null;
	private null|int|string $expectedExceptionCode = null;

	/**
	 * @param ReflectionClass<TestCase> $class
	 */
	public function __construct(
		private ReflectionClass $class,
		private ReflectionMethod $method,
	) {
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	public function run() : PromiseInterface {
		$test = $this->getInstance();

		return $this->setUp($test)
			->then(function () use ($test) : PromiseInterface {
				return $this->method->invoke($test)
					->finally(fn (mixed $ret = null) => $this->tearDown($test, $ret));
			})
			->then(function () {
				$this->expectedExceptionWasNotRaised();

				return null;
			})
			->catch(function (Throwable $th) {
				if (!$this->shouldExceptionExpectationsBeVerified($th)) {
					throw $th;
				}

				$this->verifyExceptionExpectations($th);
			})
			->catch(fn (mixed $ret = null) => $this->tearDown($test, $ret));
	}

	/**
	 * @return PromiseInterface<null>
	 */
	public function tearDown(object $test, mixed $exception = null) : PromiseInterface {
		$tearDownMethod = $this->class->getMethod("tearDown");

		return $tearDownMethod->invoke($test)
			->finally(function () use ($exception) : void {
				if ($exception !== null && $exception instanceof Throwable) {
					throw $exception;
				}
			});
	}

	/**
	 * @return PromiseInterface<null>
	 */
	public function setUp(object $test) : PromiseInterface {
		$setUpMethod = $this->class->getMethod("setUp");

		return $setUpMethod->invoke($test);
	}

	public function __toString() : string {
		return $this->class->getName() . "::" . $this->method->getName();
	}

	public function onLoad() : void {
		$method = $this->class->getMethod("onLoad");
		if ($method->isPublic() && !$method->isAbstract()) {
			$method->invoke($this->getInstance());
		}
	}

	public function onEnable() : void {
		$method = $this->class->getMethod("onEnable");
		if ($method->isPublic() && !$method->isAbstract()) {
			$method->invoke($this->getInstance());
		}
	}

	public function onDisable() : void {
		$method = $this->class->getMethod("onDisable");
		if ($method->isPublic() && !$method->isAbstract()) {
			$method->invoke($this->getInstance());
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

	private function getInstance() : TestCase {
		return $this->class->newInstance($this);
	}
}
