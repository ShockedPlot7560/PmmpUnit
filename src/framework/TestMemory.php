<?php

namespace ShockedPlot7560\PmmpUnit\framework;

class TestMemory {
	public static ?RunnableTest $currentTest = null;

	/** @var array<class-string, true> */
	public static array $loadedClasses = [];

	/** @var array<class-string, true> */
	public static array $enabledClasses = [];

	/** @var array<class-string, true> */
	public static array $disabledClasses = [];
}
