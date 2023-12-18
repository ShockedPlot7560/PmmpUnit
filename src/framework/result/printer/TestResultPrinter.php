<?php

namespace ShockedPlot7560\PmmpUnit\framework\result\printer;

use ShockedPlot7560\PmmpUnit\framework\result\FailedTest;
use ShockedPlot7560\PmmpUnit\framework\result\FatalTest;

class TestResultPrinter {
	public function __construct(
		private TestResultsBag $resultsBag
	) {	}

    public function print(\Logger $logger) : void {
        $logger->notice("=== Tests Results ===");
        $this->printFatal($logger);
        $this->printFailed($logger);
        $this->printStats($logger);
        $logger->notice("====================");
    }

    private function printFatal(\Logger $logger): void {
        if(count($errors = $this->resultsBag->getFatalErrors()) === 0){
            return;
        }
        $logger->error("Fatal errors occurred during tests:");
        $i = 0;
        foreach ($errors as $error) {
            $errorString = ++$i . ") " . $this->errorToString($error);
            $logger->error($errorString);
            $logger->logException($error->throwable);
        }
    }

    private function printFailed(\Logger $logger) : void {
        if(count($errors = $this->resultsBag->getFailedTests()) === 0){
            return;
        }
        $logger->emergency("Failed tests:");
        $i = 0;
        foreach ($errors as $error) {
            $errorString = ++$i . ") " . $this->errorToString($error);
            $logger->emergency($errorString);
        }
    }

    private function printStats(\Logger $logger) : void {
        $failedTests = $this->resultsBag->getFailedTests();
        $fatalErrors = $this->resultsBag->getFatalErrors();
        $passedTests = $this->resultsBag->getPassedTests();
        $logger->notice("Total tests: " . count($this->resultsBag->getAllTests()));
        $logger->notice("Passed tests: " . count($passedTests) . " (" . $this->resultsBag->getSuccessRate() . "%)");
        $logger->info((count($failedTests) > 0 ? "§4" : "") . "Total failed: " . count($failedTests) . " (" . $this->resultsBag->getFailedRate() . "%)");
        $logger->info(
            (count($fatalErrors) > 0 ? "§c" : "") . "Total fatal: " . count($fatalErrors) . " (" . $this->resultsBag->getFatalRate() . "%)\n" .
            "Heatmap: §a" . $this->resultsBag->getSuccessRate() . "% §c" . $this->resultsBag->getFailedRate() . "% §4" . $this->resultsBag->getFatalRate() . "%\n" .
            (new HeatmapCalculator($this->resultsBag))->calculateHeatmap()
        );
    }

    private function errorToString(FatalTest|FailedTest $error) : string {
        $ret = $error->test->__toString() . ": ";
        $ret .= str_replace("§", "&", $error->throwable->getMessage());
        $ret .= " (line: " . $error->throwable->getLine() . ")";

        return $ret;
    }
}
