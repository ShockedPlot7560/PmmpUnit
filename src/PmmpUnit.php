<?php

declare(strict_types=1);

namespace ShockedPlot7560\PmmpUnit;

use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginDescription;
use pocketmine\plugin\PluginLoader;
use pocketmine\plugin\ResourceProvider;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;
use pocketmine\utils\Process;
use pocketmine\utils\SingletonTrait;
use ReflectionClass;
use RuntimeException;
use ShockedPlot7560\PmmpUnit\framework\CurrentTest;
use ShockedPlot7560\PmmpUnit\framework\result\FailedTest;
use ShockedPlot7560\PmmpUnit\framework\result\FatalTest;
use ShockedPlot7560\PmmpUnit\framework\result\ServerCrashedException;
use ShockedPlot7560\PmmpUnit\framework\result\SuccessTest;
use ShockedPlot7560\PmmpUnit\framework\result\TestResults;
use ShockedPlot7560\PmmpUnit\framework\RunnableTest;
use ShockedPlot7560\PmmpUnit\framework\TestSuite;
use ShockedPlot7560\PmmpUnit\players\PlayerBag;
use ShockedPlot7560\PmmpUnit\players\TestPlayerManager;

class PmmpUnit extends PluginBase {
	use SingletonTrait;
	private RunnableTest $test;
	private TestPlayerManager $playerManager;
	private PlayerBag $playerBag;

	public function __construct(PluginLoader $loader, Server $server, PluginDescription $description, string $dataFolder, string $file, ResourceProvider $resourceProvider) {
		self::setInstance($this);

		$vendorDir = dirname(__DIR__) . "/vendor";
		if (!str_contains($vendorDir, "phar://") && is_dir($vendorDir . "/pocketmine")) {
			$server->getLogger()->info("Found pocketmine vendor folder, installing composer dependencies without dev");
			$exit = Process::execute("cd " . dirname(__DIR__) . " && composer install --no-dev --prefer-dist --optimize-autoloader", $stdout, $stderr);
			if ($exit !== 0) {
				throw new RuntimeException("Failed to run composer install: " . $stdout . " " . $stderr);
			} else {
				$server->getLogger()->info("Composer install successful");
			}
		}

		include_once dirname(__DIR__) . "/vendor/autoload.php";
		parent::__construct($loader, $server, $description, $dataFolder, $file, $resourceProvider);
	}

	protected function onLoad() : void {
		$unitFolder = $this->getDataFolder() . "tests";
		if (!is_dir($unitFolder)) {
			$this->getLogger()->warning("Unit test folder ($unitFolder) not found, creating one...");
			mkdir($unitFolder);
		}

		$testSuite = getenv("TEST_SUITE");
		if ($testSuite !== false) {
			$unitFolder .= "/" . $testSuite;
			if (!is_dir($unitFolder)) {
				$this->getLogger()->warning("Unit test folder ($unitFolder) not found, creating one...");
				mkdir($unitFolder);
			}
		}

		$this->getLogger()->debug("Loading tests from $unitFolder");

		$this->test = TestSuite::fromDirectory($unitFolder);

		// prevent server waiting, so we can run tests faster for CI
		$reflectionServer = new ReflectionClass(Server::getInstance());
		$startTimeProperty = $reflectionServer->getProperty("startTime");
		$startTimeProperty->setValue(Server::getInstance(), time() - 240);

		$this->playerManager = new TestPlayerManager($this);
		$this->playerBag = new PlayerBag();
		$this->test->onLoad();
	}

	public function getTestPlayerManager() : TestPlayerManager {
		return $this->playerManager;
	}

	public function getPlayerBag() : PlayerBag {
		return $this->playerBag;
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
		if (CurrentTest::$currentTest !== null) {
			global $lastExceptionError, $lastError;
			if (isset($lastExceptionError)) {
				$error = $lastExceptionError;
			} else {
				$error = $lastError;
			}
			TestResults::fatalTest(CurrentTest::$currentTest, ServerCrashedException::fromArray($error));
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
