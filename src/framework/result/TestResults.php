<?php

namespace ShockedPlot7560\PmmpUnit\framework\result;

use ShockedPlot7560\PmmpUnit\framework\RunnableTest;
use Throwable;

class TestResults {
	/** @var TestResult[] */
	private static array $testResults = [];

	public static function successTest(RunnableTest $test) : void {
		self::$testResults[] = new SuccessTest($test);
	}

	public static function failedTest(RunnableTest $test, Throwable $throwable) : void {
		self::$testResults[] = new FailedTest($test, $throwable);
	}

	public static function fatalTest(RunnableTest $test, Throwable $throwable) : void {
		self::$testResults[] = new FatalTest($test, $throwable);
	}

	/**
	 * @return TestResult[]
	 */
	public static function getTestResults() : array {
		return self::$testResults;
	}
}
