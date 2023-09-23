<?php

namespace ShockedPlot7560\PmmpUnit\framework;

use React\Promise\PromiseInterface;
use function React\Promise\resolve;
use ShockedPlot7560\PmmpUnit\framework\assert\BaseAssert;
use ShockedPlot7560\PmmpUnit\framework\assert\PocketmineSpecificAssert;
use ShockedPlot7560\PmmpUnit\PmmpUnit;

class TestCase extends BaseAssert {
	use PocketmineSpecificAssert;

	public function __construct(
		private readonly ExpectedExceptionHandler $exceptionHandler
	) {
	}

	public function getExceptionHandler() : ExpectedExceptionHandler {
		return $this->exceptionHandler;
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
			PmmpUnit::getInstance()->getTestPlayerManager()->removePlayer($player->getPlayer());
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
		$this->exceptionHandler->expectException($exception);
	}

	final public function expectExceptionCode(int|string $code) : void {
		$this->exceptionHandler->expectExceptionCode($code);
	}

	final public function expectExceptionMessage(string $message) : void {
		$this->exceptionHandler->expectExceptionMessage($message);
	}

	final public function expectExceptionMessageMatches(string $regularExpression) : void {
		$this->exceptionHandler->expectExceptionMessageMatches($regularExpression);
	}
}
