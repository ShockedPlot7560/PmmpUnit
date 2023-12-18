<?php

namespace ShockedPlot7560\PmmpUnit\framework\result\printer;

use ShockedPlot7560\PmmpUnit\framework\result\FailedTest;
use ShockedPlot7560\PmmpUnit\framework\result\FatalTest;
use ShockedPlot7560\PmmpUnit\framework\result\SuccessTest;
use ShockedPlot7560\PmmpUnit\framework\result\TestResult;

class TestResultsBag {
	/** @var FatalTest[] $fatalErrors */
	private array $fatalErrors = [];

	/** @var FailedTest[] $failedTests */
	private array $failedTests = [];

	/** @var SuccessTest[] $passedTests */
	private array $passedTests = [];

	/**
	 * @param TestResult[] $testResults
	 */
	public function __construct(
		private array $testResults
	) {
		foreach ($testResults as $result) {
			if ($result instanceof FailedTest) {
				$this->failedTests[] = $result;
			} elseif ($result instanceof SuccessTest) {
				$this->passedTests[] = $result;
			} elseif ($result instanceof FatalTest) {
				$this->fatalErrors[] = $result;
			}
		}
	}

	/**
	 * @return TestResult[]
	 */
	public function getAllTests() : array {
		return $this->testResults;
	}

	/**
	 * @return FailedTest[]
	 */
	public function getFailedTests() : array {
		return $this->failedTests;
	}

	/**
	 * @return SuccessTest[]
	 */
	public function getPassedTests() : array {
		return $this->passedTests;
	}

	/**
	 * @return FatalTest[]
	 */
	public function getFatalErrors() : array {
		return $this->fatalErrors;
	}

	public function isFailed(TestResult $result) : bool {
		return $result instanceof FailedTest;
	}

	public function isFatal(TestResult $result) : bool {
		return $result instanceof FatalTest;
	}

	public function isPassed(TestResult $result) : bool {
		return $result instanceof SuccessTest;
	}

	public function getSuccessRate() : float {
		if (count($this->testResults) === 0) {
			return 100;
		}

		return round(count($this->passedTests) / count($this->testResults) * 100, 2);
	}

	public function getFailedRate() : float {
		if (count($this->testResults) === 0) {
			return 0;
		}

		return round(count($this->failedTests) / count($this->testResults) * 100, 2);
	}

	public function getFatalRate() : float {
		if (count($this->testResults) === 0) {
			return 0;
		}

		return round(count($this->fatalErrors) / count($this->testResults) * 100, 2);
	}

	/**
	 * @return TestResult[]
	 */
	public function getAllErrors() : array {
		return array_merge($this->failedTests, $this->fatalErrors);
	}
}
