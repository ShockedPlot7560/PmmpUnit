<?php

namespace ShockedPlot7560\PmmpUnit\framework\result;

use ShockedPlot7560\PmmpUnit\framework\runner\TestRunnerInterface;
use Throwable;

class TestResults {
	/** @var TestResult[] */
	private static array $testResults = [];

	public static function successTest(TestRunnerInterface $test) : void {
		self::$testResults[] = new SuccessTest($test);
	}

	public static function failedTest(TestRunnerInterface $test, Throwable $throwable) : void {
		self::$testResults[] = new FailedTest($test, $throwable);
	}

	public static function fatalTest(TestRunnerInterface $test, Throwable $throwable) : void {
		self::$testResults[] = new FatalTest($test, $throwable);
	}

	public static function fatal(Throwable $throwable) : void {
		self::$testResults[] = new Fatal($throwable);
	}

	/**
	 * @return TestResult[]
	 */
	public static function getTestResults() : array {
		return self::$testResults;
	}
}
