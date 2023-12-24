<?php

namespace ShockedPlot7560\PmmpUnit\framework\runner;

use Iterator;

interface IterableTest {
	/**
	 * @phpstan-return \Iterator<TestRunnerInterface>
	 */
	public function getIterator() : Iterator;
}
