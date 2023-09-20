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
		private string $name
	) {
	}

	public function run() : PromiseInterface {
		$iterator = new ArrayIterator($this->tests);
		foreach ($this->testMethods as $testMethod) {
			$iterator->append($testMethod);
		}

		return $this->runRec($iterator);
	}

	/**
	 * @param Iterator<RunnableTest> $iterator
	 */
	private function runRec(Iterator $iterator) {
		if ($iterator->valid()) {
			$test = $iterator->current();
			self::$currentTest = $test;
			$iterator->next();

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
				return $promise->then(function () use ($iterator) {
					self::$currentTest = null;

					return $this->runRec($iterator);
				});
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

	public function addTestFiles(iterable $filenames) : void {
		foreach ($filenames as $filename) {
			$this->addTestFile($filename);
		}
	}

	public function addTestFile(string $filename) : void {
		$this->addTestSuite((new TestSuiteLoader())->load($filename));
	}

	public function addTestSuite(ReflectionClass $class) : void {
		TestSuiteChecker::check($class);

		$this->addTest(self::fromClassReflection($class));
	}

	public static function fromClassReflection(ReflectionClass $class) : RunnableTest {
		$testSuite = new static($class->getName());

		foreach (Utils::getTestMethodsInTestCase($class) as $method) {
			// if ($method->getDeclaringClass()->getName() === Assert::class) {
			// 	continue;
			// }

			// if ($method->getDeclaringClass()->getName() === TestCase::class) {
			// 	continue;
			// }

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

	public function addTestMethod(ReflectionClass $class, ReflectionMethod $method) : void {
		$this->testMethods[] = new TestMethod($class, $method);
	}
}
