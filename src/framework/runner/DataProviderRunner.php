<?php

namespace ShockedPlot7560\PmmpUnit\framework\runner;

use ArrayIterator;
use Closure;
use Iterator;
use React\Promise\PromiseInterface;
use ReflectionClass;
use ReflectionMethod;
use ShockedPlot7560\PmmpUnit\framework\attribute\DataProviderAttribute;
use ShockedPlot7560\PmmpUnit\framework\TestCase;
use Webmozart\Assert\Assert;

class DataProviderRunner extends EndChildRunner implements IterableTest {
	/**
	 * @phpstan-param ReflectionClass<TestCase> $class
	 */
	public function __construct(
		ReflectionClass $class,
		ReflectionMethod $method,
		private DataProviderAttribute $attribute
	) {
		parent::__construct($class, $method);

		$methodProvider = $this->attribute->provider;
		Assert::true($this->class->hasMethod($methodProvider), "Method $methodProvider does not exist in class {$this->class->getName()}");
		Assert::false($this->class->getMethod($methodProvider)->isStatic(), "Method $methodProvider is static in class {$this->class->getName()}");
		Assert::true($this->class->getMethod($methodProvider)->isPublic(), "Method $methodProvider is not public in class {$this->class->getName()}");
	}

	/**
	 * @phpstan-return PromiseInterface<null>
	 */
	public function run() : PromiseInterface {
		return TestRunnerUtils::runRec($this->getIterator());
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

	public function getIterator() : Iterator {
		$test = $this->getInstance(false);
		$data = $this->getDataProviderAsClosure()->call($this, $test);
		$iterator = new ArrayIterator();
		foreach ($data as $key => $args) {
			Assert::isArray($args);
			$iterator->append(new MethodFeededRunner(
				$this->class,
				$this->method,
				$args,
				is_string($key) ? $key : null,
			));
		}

		return $iterator;
	}

	/**
	 * @phpstan-return Closure(TestCase $object): iterable<iterable<mixed>>
	 */
	private function getDataProviderAsClosure() : Closure {
		$provider = $this->attribute->provider;

		return function (TestCase $object) use ($provider) : iterable {
			return $this->class->getMethod($provider)->invoke($object);
		};
	}

	public function __toString() : string {
		return $this->class->getName() . "::" . $this->method->getName() . " with data provider";
	}
}
