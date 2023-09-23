<?php

namespace ShockedPlot7560\PmmpUnit\tests\normal;

use React\Promise\PromiseInterface;
use function React\Promise\resolve;
use ShockedPlot7560\PmmpUnit\framework\attribute\TestAttribute;
use ShockedPlot7560\PmmpUnit\framework\TestCase;

class TestNotTestableTest extends TestCase {
	private static bool $testRun = false;

	public function testNoop() : PromiseInterface {
		return resolve(null);
	}

	#[TestAttribute]
	public function aFunctionToTest() : PromiseInterface {
		self::$testRun = true;

		return resolve(null);
	}

	public function onDisable() : void {
		self::assertTrue(self::$testRun, "Test for testing TestAttribute was not run");
	}
}
