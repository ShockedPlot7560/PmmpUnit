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
		$methodProvider = $this->attribute->getProvider();
		Assert::true($this->class->hasMethod($methodProvider), "Method $methodProvider does not exist in class {$this->class->getName()}");
		Assert::false($this->class->getMethod($methodProvider)->isStatic(), "Method $methodProvider is static in class {$this->class->getName()}");
		Assert::true($this->class->getMethod($methodProvider)->isPublic(), "Method $methodProvider is not public in class {$this->class->getName()}");
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
		$data = $this->getDataProvidingClosure()->call($this, $test);
		$iterator = new ArrayIterator();
		foreach ($data as $key => $args) {
			Assert::isArray($args);
			$iterator->append(new TestMethodFeeded(
				$this->class,
				$this->method,
				$args,
				is_string($key) ? $key : null,
			));
		}

		return $iterator;
	}

	/**
	 * @phpstan-param ReflectionClass<TestCase> $class
	 * @return Closure(TestCase $object): array<iterable<mixed>>
	 */
	private function getDataProvidingClosure() : Closure {
		$provider = $this->attribute->getProvider();
		$closure = function (TestCase $object) use ($provider) : iterable {
			return $this->class->getMethod($provider)->invoke($object);
		};

		return $closure;
	}

	public function __toString() : string {
		return $this->class->getName() . "::" . $this->method->getName() . " with data provider";
	}
}
