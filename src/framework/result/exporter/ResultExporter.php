<?php

namespace ShockedPlot7560\PmmpUnit\framework\result\exporter;

use pocketmine\plugin\Plugin;
use ShockedPlot7560\PmmpUnit\framework\result\printer\TestResultsBag;

abstract class ResultExporter {
	public function __construct(
		protected Plugin $plugin,
		protected TestResultsBag $results
	) {
	}

	abstract public function export() : void;
}
