<?php

namespace ShockedPlot7560\PmmpUnit\framework;

use ShockedPlot7560\PmmpUnit\framework\runner\TestRunnerInterface;

class TestMemory {
	public static ?TestRunnerInterface $currentTest = null;

	/** @var array<class-string, true> */
	public static array $loadedClasses = [];

	/** @var array<class-string, true> */
	public static array $enabledClasses = [];

	/** @var array<class-string, true> */
	public static array $disabledClasses = [];
}
