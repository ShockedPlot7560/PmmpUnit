<?php

declare(strict_types=1);

namespace ShockedPlot7560\UnitTest;

use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginDescription;
use pocketmine\plugin\PluginLoader;
use pocketmine\plugin\ResourceProvider;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;
use ShockedPlot7560\UnitTest\framework\result\FailedTest;
use ShockedPlot7560\UnitTest\framework\result\FatalTest;
use ShockedPlot7560\UnitTest\framework\result\ServerCrashedException;
use ShockedPlot7560\UnitTest\framework\result\SuccessTest;
use ShockedPlot7560\UnitTest\framework\result\TestResults;
use ShockedPlot7560\UnitTest\framework\RunnableTest;
use ShockedPlot7560\UnitTest\framework\TestSuite;

class UnitTest extends PluginBase {
	use SingletonTrait;
	private RunnableTest $test;

	public function __construct(PluginLoader $loader, Server $server, PluginDescription $description, string $dataFolder, string $file, ResourceProvider $resourceProvider) {
		self::setInstance($this);

		include_once dirname(__DIR__) . "/vendor/autoload.php";

		$unitFolder = $dataFolder . "/tests";
		if (!is_dir($unitFolder)) {
			$server->getLogger()->warning("Unit test folder not found, creating one...");
			mkdir($unitFolder);
		}

		$this->test = TestSuite::fromDirectory($unitFolder);
		parent::__construct($loader, $server, $description, $dataFolder, $file, $resourceProvider);
	}

	protected function onLoad() : void {
		$this->test->onLoad();
	}

	protected function onEnable() : void {
		$this->test->onEnable();
		$this->getScheduler()->scheduleDelayedTask(new ClosureTask(function () {
			$this->test->run()
				->then(function () {
					$this->finish();
				});
		}), 0);
	}

	public function onDisable() : void {
		$this->test->onDisable();
		if (TestSuite::$currentTest !== null) {
			global $lastExceptionError, $lastError;
			if (isset($lastExceptionError)) {
				$error = $lastExceptionError;
			} else {
				$error = $lastError;
			}
			TestResults::fatalTest(TestSuite::$currentTest, ServerCrashedException::fromArray($error));
			$this->finish(false);
		}
	}

	private function finish(bool $close = true) : void {
		$results = TestResults::getTestResults();
		$fatalErrors = [];
		$failedTests = [];
		$passedTests = [];
		$heatmap = "Heatmap: \n";
		foreach ($results as $result) {
			if ($result instanceof FailedTest) {
				$failedTests[] = $result;
				$heatmap .= "§4X";
			} elseif ($result instanceof SuccessTest) {
				$passedTests[] = $result;
				$heatmap .= "§aO";
			} elseif ($result instanceof FatalTest) {
				$fatalErrors[] = $result;
				$heatmap .= "§c!";
			}
		}

		$this->getLogger()->notice("=== Unit Test Results ===");
		if (count($fatalErrors) > 0) {
			$this->getLogger()->error("Fatal errors occurred during unit test:");
			$i = 0;
			foreach ($fatalErrors as $error) {
				$this->getLogger()->error(++$i . ") " . $error->test->__toString() . ": ");
				$this->getLogger()->logException($error->throwable);
			}
		}

		if (count($failedTests) > 0) {
			$this->getLogger()->emergency("Failed tests:");
			$i = 0;
			foreach ($failedTests as $error) {
				$this->getLogger()->emergency(++$i . ") " . $error->test->__toString() . ": " . $error->throwable->getMessage());
			}
		}

		$this->getLogger()->notice("Total tests: " . count($results));
		$this->getLogger()->info("  Total passed: " . count($passedTests) . " (" . round(count($passedTests) / count($results) * 100, 2) . "%)");
		$this->getLogger()->info((count($failedTests) > 0 ? "§4" : "") . "  Total failed: " . count($failedTests) . " (" . round(count($failedTests) / count($results) * 100, 2) . "%)");
		$this->getLogger()->info((count($fatalErrors) > 0 ? "§c" : "") . "  Total fatal: " . count($fatalErrors) . " (" . round(count($fatalErrors) / count($results) * 100, 2) . "%)");
		$this->getLogger()->info($heatmap);

		$this->getLogger()->notice("=== ============ ===");

		if ($close) {
			$this->getServer()->shutdown();
		}
	}
}
