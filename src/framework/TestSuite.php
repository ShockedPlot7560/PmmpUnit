<?php

namespace ShockedPlot7560\UnitTest\framework;

use ArrayIterator;
use Iterator;
use React\Promise\PromiseInterface;
use function React\Promise\resolve;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use ReflectionClass;
use ReflectionMethod;
use RegexIterator;
use ShockedPlot7560\UnitTest\framework\loader\TestSuiteChecker;
use ShockedPlot7560\UnitTest\framework\loader\TestSuiteLoader;
use ShockedPlot7560\UnitTest\framework\result\TestResults;
use ShockedPlot7560\UnitTest\Utils\Utils;
use Throwable;
use Webmozart\Assert\InvalidArgumentException;

class TestSuite implements RunnableTest {
	/** @var TestMethod[] */
	private array $testMethods = [];

	/** @var RunnableTest[] */
	private array $tests = [];
	public static ?RunnableTest $currentTest = null;

	final private function __construct(
		public readonly string $name
	) {
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	public function run() : PromiseInterface {
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
	private function getIterator() : Iterator {
		$iterator = new ArrayIterator($this->tests);
		foreach ($this->testMethods as $testMethod) {
			$iterator->append($testMethod);
		}

		return $iterator;
	}

	/**
	 * @param Iterator<RunnableTest> $iterator
	 * @phpstan-return PromiseInterface<null>
	 */
	private function runRec(Iterator $iterator) : PromiseInterface {
		if ($iterator->valid()) {
			$test = $iterator->current();
			self::$currentTest = $test;
			$iterator->next();

			$promise = null;
			try {
				$promise = $test->run()
					->then(function () use ($test) {
						TestResults::successTest($test);
					}, function (Throwable $throwable) use ($test) {
						TestResults::failedTest($test, $throwable);
					});
			} catch (InvalidArgumentException $assertFailed) {
				TestResults::failedTest($test, $assertFailed);
				$promise = resolve(null);
			} finally {
				return $promise?->then(function () use ($iterator) {
					self::$currentTest = null;

					return $this->runRec($iterator);
				}) ?? resolve(null);
			}
		} else {
			return resolve(null);
		}
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

			$testSuite->addTestMethod($class, $method);
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
}
