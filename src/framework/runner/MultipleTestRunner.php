<?php

namespace ShockedPlot7560\PmmpUnit\framework\runner;

use ArrayIterator;
use Iterator;

abstract class MultipleTestRunner implements IterableTest {
	/** @var TestRunnerInterface[] */
	protected array $childs = [];

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

	protected function addTest(TestRunnerInterface $test) : void {
		$this->childs[] = $test;
	}

	/**
	 * @phpstan-return \Iterator<TestRunnerInterface>
	 */
	public function getIterator() : Iterator {
		$iterator = new ArrayIterator();
		foreach ($this->childs as $child) {
			if ($child instanceof IterableTest) {
				foreach ($child->getIterator() as $subChild) {
					$iterator->append($subChild);
				}
			} else {
				$iterator->append($child);
			}
		}

		return $iterator;
	}
}
