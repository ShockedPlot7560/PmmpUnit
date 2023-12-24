<?php

namespace ShockedPlot7560\PmmpUnit\framework\runner;

use pocketmine\Server;
use React\Promise\PromiseInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use RegexIterator;
use ShockedPlot7560\PmmpUnit\framework\loader\exception\LoaderException;
use ShockedPlot7560\PmmpUnit\framework\loader\TestSuiteChecker;
use ShockedPlot7560\PmmpUnit\framework\loader\TestSuiteLoader;
use ShockedPlot7560\PmmpUnit\framework\TestCase;

class TestRunner extends MultipleTestRunner {
	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	public function run() : PromiseInterface {
		Server::getInstance()->getLogger()->debug("Starting root test suite");

		return TestRunnerUtils::runRec($this->getIterator());
	}

	public static function fromDirectory(string $path) : self {
		$runner = new self();
		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
		$iterator = new RegexIterator($iterator, '/^.+\.php$/i', RegexIterator::GET_MATCH);
		foreach ($iterator as $file) {
			$runner->addTestFile($file[0]);
		}

		return $runner;
	}

	public function addTestFile(string $filename) : void {
		$this->addTestSuite((new TestSuiteLoader())->load($filename));
	}

	/**
	 * @param ReflectionClass<TestCase> $class
	 */
	public function addTestSuite(ReflectionClass $class) : void {
		try {
			TestSuiteChecker::check($class);
		} catch (LoaderException $e) {
			return;
		}

		$this->addTest(SuiteTestRunner::fromClassReflection($class));
	}
}
