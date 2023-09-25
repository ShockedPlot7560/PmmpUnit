<?php

namespace ShockedPlot7560\PmmpUnit\tests\normal;

use React\Promise\PromiseInterface;
use ShockedPlot7560\PmmpUnit\framework\TestCase;

class StatusFunctionTest extends TestCase {
	private static int $loadCount = 0;
	private static int $enableCount = 0;

	public function onLoad() : void {
		self::$loadCount++;
	}

	public function onEnable() : void {
		self::$enableCount++;
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	public function testLoadFunctionExecutedOneTime() : PromiseInterface {
		return $this->assertEquals(1, self::$loadCount);
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	public function testEnableFunctionExecutedOneTime() : PromiseInterface {
		return $this->assertEquals(1, self::$enableCount);
	}

	//disable can't be tested because we don't have post shutdown hook
}
