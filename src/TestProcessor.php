<?php

namespace ShockedPlot7560\PmmpUnit;

use Logger;
use pocketmine\utils\SingletonTrait;
use PrefixedLogger;
use ShockedPlot7560\PmmpUnit\framework\result\exporter\FileResultExporter;
use ShockedPlot7560\PmmpUnit\framework\result\printer\TestResultPrinter;
use ShockedPlot7560\PmmpUnit\framework\result\printer\TestResultsBag;
use ShockedPlot7560\PmmpUnit\framework\result\ServerCrashedException;
use ShockedPlot7560\PmmpUnit\framework\result\TestResults;
use ShockedPlot7560\PmmpUnit\framework\runner\TestRunner;
use ShockedPlot7560\PmmpUnit\framework\TestMemory;
use ShockedPlot7560\PmmpUnit\players\PlayerBag;
use ShockedPlot7560\PmmpUnit\players\TestPlayerManager;
use Throwable;

class TestProcessor {
	use SingletonTrait;
	private Logger $logger;
	private TestRunner $runner;
	private TestPlayerManager $playerManager;
	private PlayerBag $playerBag;

	public function __construct(
		private PmmpUnit $pmmpUnit
	) {
		$this->logger = new PrefixedLogger($this->pmmpUnit->getServer()->getLogger(), "Integration tests");
		self::setInstance($this);
	}

	public function setup() : void {
		$unitFolder = $this->pmmpUnit->getDataFolder() . "tests";
		if (!is_dir($unitFolder)) {
			$this->getLogger()->warning("Tests folder ($unitFolder) not found, creating one...");
			mkdir($unitFolder);
		}

		$testSuite = getenv("TEST_SUITE");
		if ($testSuite !== false) {
			$unitFolder .= "/" . $testSuite;
			if (!is_dir($unitFolder)) {
				$this->getLogger()->warning("Tests folder ($unitFolder) not found, creating one...");
				mkdir($unitFolder);
			}
		}

		$this->getLogger()->debug("Loading tests from $unitFolder");

		$this->runner = TestRunner::fromDirectory($unitFolder);

		$this->playerManager = new TestPlayerManager($this->pmmpUnit);
		$this->playerBag = new PlayerBag();
		$this->runner->onLoad();
	}

	public function prepare() : void {
		$this->runner->onEnable();
	}

	public function start() : void {
		$this->runner->run()
			->then(function () {
				$this->finish();
			})
			->catch(function (Throwable $e) {
				$this->getLogger()->logException($e);
				$this->finish();
			});
	}

	public function stop() : void {
		$this->runner->onDisable();
		if (TestMemory::$currentTest !== null) {
			global $lastExceptionError, $lastError;
			$error = $lastExceptionError ?? $lastError;
			TestResults::fatalTest(TestMemory::$currentTest, ServerCrashedException::fromArray($error));
			$this->finish(false);
		}
	}

	public function getLogger() : Logger {
		return $this->logger;
	}

	public function getPlayerManager() : TestPlayerManager {
		return $this->playerManager;
	}

	public function getPlayerBag() : PlayerBag {
		return $this->playerBag;
	}

	private function finish(bool $close = true) : void {
		$results = TestResults::getTestResults();

		$resultBag = new TestResultsBag($results);
		$printer = new TestResultPrinter($resultBag);

		$printer->print($this->logger);

		$exporter = new FileResultExporter($this->pmmpUnit, $resultBag);
		$exporter->export();

		if ($close) {
			$this->pmmpUnit->getServer()->shutdown();
		}
	}
}
