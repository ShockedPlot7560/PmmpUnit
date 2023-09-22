<?php

namespace ShockedPlot7560\UnitTest\framework;

use React\Promise\PromiseInterface;
use ShockedPlot7560\UnitTest\framework\assert\BaseAssert;
use ShockedPlot7560\UnitTest\framework\assert\PocketmineSpecificAssert;
use ShockedPlot7560\UnitTest\UnitTest;
use function React\Promise\resolve;

class TestCase extends BaseAssert {
	use PocketmineSpecificAssert;

	public function __construct(
		private readonly TestMethod $testMethod
	) {
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	public function setUp() : PromiseInterface {
		return resolve(null);
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	public function tearDown() : PromiseInterface {
		foreach ($this->spawnedPlayers as $player) {
			UnitTest::getInstance()->getTestPlayerManager()->removePlayer($player->getPlayer());
		}

		return resolve(null);
	}

	public function onLoad() : void {
	}

	public function onEnable() : void {
	}

	public function onDisable() : void {
	}

	/**
	 * @phpstan-param class-string<\Throwable> $exception
	 */
	final public function expectException(string $exception) : void {
		$this->testMethod->expectException($exception);
	}

	final public function expectExceptionCode(int|string $code) : void {
		$this->testMethod->expectExceptionCode($code);
	}

	final public function expectExceptionMessage(string $message) : void {
		$this->testMethod->expectExceptionMessage($message);
	}

	final public function expectExceptionMessageMatches(string $regularExpression) : void {
		$this->testMethod->expectExceptionMessageMatches($regularExpression);
	}
}
