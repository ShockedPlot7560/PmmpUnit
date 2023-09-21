<?php

namespace ShockedPlot7560\UnitTest\framework;

use React\Promise\PromiseInterface;

interface RunnableTest {
	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	public function run() : PromiseInterface;

	public function onLoad() : void;

	public function onEnable() : void;

	public function onDisable() : void;
}
