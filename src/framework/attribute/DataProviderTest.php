<?php

namespace ShockedPlot7560\PmmpUnit\framework\attribute;

use ArrayIterator;
use Closure;
use Iterator;
use React\Promise\PromiseInterface;
use ReflectionClass;
use ReflectionMethod;
use ShockedPlot7560\PmmpUnit\framework\CommonTestFunctions;
use ShockedPlot7560\PmmpUnit\framework\ExceptionExpectationHandler;
use ShockedPlot7560\PmmpUnit\framework\MultipleTestRunner;
use ShockedPlot7560\PmmpUnit\framework\RunnableTest;
use ShockedPlot7560\PmmpUnit\framework\TestCase;
use Webmozart\Assert\Assert;

class DataProviderTest implements RunnableTest, ExceptionExpectationHandler {
	use CommonTestFunctions {
		CommonTestFunctions::run as private runCommon;
	}
	use MultipleTestRunner;

	public function __construct(
		private ReflectionClass $class,
		private ReflectionMethod $method,
		private DataProviderAttribute $attribute
	) {
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	public function run() : PromiseInterface {
		return $this->runRec($this->getIterator());
	}

	/**
	 * @phpstan-return Iterator<RunnableTest>
	 */
	public function getIterator() : Iterator {
		$test = $this->getInstance(false);
		$data = $this->getDataProvidingClosure($this->class)->call($this, $test);
		$iterator = new ArrayIterator();
		foreach ($data as $args) {
			Assert::isArray($args);
			$iterator->append(new TestMethodFeeded($this->class, $this->method, $args));
		}

		return $iterator;
	}

	/**
	 * @phpstan-param ReflectionClass<TestCase> $class
	 * @return Closure(): array<array<mixed>>
	 */
	private function getDataProvidingClosure(ReflectionClass $class) : Closure {
		$provider = $this->attribute->getProvider();
		$closure = function (TestCase $object) use ($provider) : array {
			if (is_string($provider)) {
				return $this->class->getMethod($provider)->invoke($object);
			} else {
				return $provider->call($object);
			}
		};

		return $closure;
	}

	public function __toString() : string {
		return $this->class->getName() . "::" . $this->method->getName() . " with data provider";
	}
}
