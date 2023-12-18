<?php

namespace ShockedPlot7560\PmmpUnit\framework\result\printer;

class HeatmapCalculator {
	public const FAILED_COLOR = "§4";
	public const FAILED_SYMBOL = "✕";
	public const FAILED_TEST = self::FAILED_COLOR . self::FAILED_SYMBOL;
	public const CRITICAL_COLOR = "§c";
	public const CRITICAL_SYMBOL = "!";
	public const CRITICAL_TEST = self::CRITICAL_COLOR . self::CRITICAL_SYMBOL;
	public const SUCCESS_COLOR = "§a";
	public const SUCCESS_SYMBOL = "✓";
	public const SUCCESS_TEST = self::SUCCESS_COLOR . self::SUCCESS_SYMBOL;

	public function __construct(
		private TestResultsBag $resultsBag
	) {
	}

	public function calculateHeatmap() : string {
		$ret = "";
		foreach ($this->resultsBag->getAllTests() as $test) {
			if ($this->resultsBag->isFailed($test)) {
				$ret .= self::FAILED_TEST;
			} elseif ($this->resultsBag->isFatal($test)) {
				$ret .= self::CRITICAL_TEST;
			} elseif ($this->resultsBag->isPassed($test)) {
				$ret .= self::SUCCESS_TEST;
			}
		}

		return $ret;
	}
}
