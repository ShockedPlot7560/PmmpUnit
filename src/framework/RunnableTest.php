<?php

namespace ShockedPlot7560\UnitTest\framework;

use React\Promise\PromiseInterface;

interface RunnableTest {
	public function run() : PromiseInterface;
}
