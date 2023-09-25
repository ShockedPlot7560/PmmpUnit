<?php

namespace ShockedPlot7560\PmmpUnit\framework;

class TestMemory {
	public static ?RunnableTest $currentTest = null;
	public static array $loadedClasses = [];
	public static array $enabledClasses = [];
	public static array $disabledClasses = [];
}
