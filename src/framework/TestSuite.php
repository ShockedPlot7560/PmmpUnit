<?php

namespace ShockedPlot7560\PmmpUnit\framework;

use ArrayIterator;
use Iterator;
use pocketmine\Server;
use React\Promise\PromiseInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use ReflectionClass;
use ReflectionMethod;
use RegexIterator;
use ShockedPlot7560\PmmpUnit\framework\attribute\dataProvider\DataProviderAttribute;
use ShockedPlot7560\PmmpUnit\framework\attribute\dataProvider\DataProviderTest;
use ShockedPlot7560\PmmpUnit\framework\loader\TestSuiteChecker;
use ShockedPlot7560\PmmpUnit\framework\loader\TestSuiteLoader;
use ShockedPlot7560\PmmpUnit\utils\Utils;

class TestSuite implements RunnableTest {
	use MultipleTestRunner;

	/** @var TestMethod[] */
	private array $testMethods = [];

	/** @var RunnableTest[] */
	private array $tests = [];

	final private function __construct(
		public readonly string $name
	) {
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	public function run() : PromiseInterface {
		Server::getInstance()->getLogger()->debug("Starting test suite: " . $this->name);

		return $this->runRec($this->getIterator());
	}

	public function onLoad() : void {
		foreach ($this->getIterator() as $test) {
			$test->onLoad();
		}
	}

	public function onEnable() : void {
		foreach ($this->getIterator() as $test) {
			$test->onEnable();
		}
	}

	public function onDisable() : void {
		foreach ($this->getIterator() as $test) {
			$test->onDisable();
		}
	}

	/**
	 * @phpstan-return Iterator<RunnableTest>
	 */
	public function getIterator() : Iterator {
		$iterator = new ArrayIterator();
		foreach ($this->tests as $test) {
			if (method_exists($test, "getIterator")) {
				foreach ($test->getIterator() as $subTest) {
					$iterator->append($subTest);
				}
			} else {
				$iterator->append($test);
			}
		}
		foreach ($this->testMethods as $testMethod) {
			$iterator->append($testMethod);
		}

		return $iterator;
	}

	public static function fromDirectory(string $path) : self {
		$suite = new self("ROOT");
		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
		$iterator = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
		foreach ($iterator as $file) {
			$suite->addTestFile($file[0]);
		}

		return $suite;
	}

	/**
	 * @param iterable<string> $filenames
	 */
	public function addTestFiles(iterable $filenames) : void {
		foreach ($filenames as $filename) {
			$this->addTestFile($filename);
		}
	}

	public function addTestFile(string $filename) : void {
		$this->addTestSuite((new TestSuiteLoader())->load($filename));
	}

	/**
	 * @param ReflectionClass<TestCase> $class
	 */
	public function addTestSuite(ReflectionClass $class) : void {
		TestSuiteChecker::check($class);

		$this->addTest(self::fromClassReflection($class));
	}

	/**
	 * @param ReflectionClass<TestCase> $class
	 */
	public static function fromClassReflection(ReflectionClass $class) : RunnableTest {
		$testSuite = new static($class->getName());

		foreach (Utils::getTestMethodsInTestCase($class) as $method) {
			if (!Utils::isTestMethod($method)) {
				continue;
			}

			$attributes = $method->getAttributes();
			$addTestMethod = true;
			foreach ($attributes as $attribute) {
				$attribute = $attribute->newInstance();
				if ($attribute instanceof DataProviderAttribute) {
					$testSuite->addTest(new DataProviderTest($class, $method, $attribute));
					$addTestMethod = false;
				}
			}

			if ($addTestMethod) {
				$testSuite->addTestMethod($class, $method);
			}
		}

		return $testSuite;
	}

	public function addTest(RunnableTest $test) : void {
		$this->tests[] = $test;
	}

	/**
	 * @param ReflectionClass<TestCase> $class
	 */
	public function addTestMethod(ReflectionClass $class, ReflectionMethod $method) : void {
		$this->testMethods[] = new TestMethod($class, $method);
	}

	public function __toString() {
		return "TestSuite: " . $this->name;
	}
}
