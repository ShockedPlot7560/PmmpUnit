<?php

namespace ShockedPlot7560\UnitTest\framework;

use React\Promise\PromiseInterface;
use function React\Promise\resolve;

class TestCase extends BaseAssert {
	public function setUp() : PromiseInterface {
		return resolve(null);
	}

	public function tearDown() : PromiseInterface {
		return resolve(null);
	}

	public function onLoad() : void {
	}

	public function onEnable() : void {
	}

	public function onDisable() : void {
	}
}
