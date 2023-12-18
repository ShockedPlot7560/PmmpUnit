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
use ShockedPlot7560\PmmpUnit\players\PlayerBag;
use ShockedPlot7560\PmmpUnit\players\TestPlayerManager;
use Throwable;

class PmmpUnit extends PluginBase {
	use SingletonTrait;
	private TestProcessor $testProcessor;

	public function __construct(PluginLoader $loader, Server $server, PluginDescription $description, string $dataFolder, string $file, ResourceProvider $resourceProvider) {
		self::setInstance($this);
		// prevent server waiting, so we can run tests faster for CI
		$reflectionServer = new ReflectionClass(Server::getInstance());
		$startTimeProperty = $reflectionServer->getProperty("startTime");
		$startTimeProperty->setValue(Server::getInstance(), time() - 240);

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
		$this->testProcessor = new TestProcessor($this);
		$this->testProcessor->setup();
	}

	public function getTestPlayerManager() : TestPlayerManager {
		return $this->testProcessor->getPlayerManager();
	}

	public function getPlayerBag() : PlayerBag {
		return $this->testProcessor->getPlayerBag();
	}

	protected function onEnable() : void {
		$this->testProcessor->prepare();
		$this->getScheduler()->scheduleDelayedTask(new ClosureTask(function () {
			$this->testProcessor->start();
		}), 0);
	}

	public function onDisable() : void {
		$this->testProcessor->stop();
	}
}
