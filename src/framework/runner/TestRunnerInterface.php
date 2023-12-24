<?php

namespace ShockedPlot7560\PmmpUnit\framework\runner;

use React\Promise\PromiseInterface;
use Stringable;

interface TestRunnerInterface extends Stringable {
	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	public function run() : PromiseInterface;

	public function onLoad() : void;

	public function onEnable() : void;

	public function onDisable() : void;
}
