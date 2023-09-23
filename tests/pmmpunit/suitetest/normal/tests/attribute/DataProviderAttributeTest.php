<?php

namespace ShockedPlot7560\PmmpUnit\tests\normal\attribute;

use ArrayIterator;
use Generator;
use Iterator;
use React\Promise\PromiseInterface;
use function React\Promise\resolve;
use ShockedPlot7560\PmmpUnit\framework\attribute\dataProvider\DataProviderAttribute;
use ShockedPlot7560\PmmpUnit\framework\TestCase;

class DataProviderAttributeTest extends TestCase {
	/**
	 * @return int[][]
	 */
	public function dataProviderArray() : array {
		return [
			[1, 2, 3],
			[2, 3, 5],
			[3, 4, 7]
		];
	}

	/**
	 * @return PromiseInterface<null>
	 */
	#[DataProviderAttribute("dataProviderArray")]
	public function testDataProviderArray(int $a, int $b, int $expected) : PromiseInterface {
		$this->assertEquals($expected, $a + $b);

		return resolve(null);
	}

	/**
	 * @return Generator<int[]>
	 */
	public function dataProviderGenerator() : Generator {
		yield [1, 2, 3];
		yield [2, 3, 5];
		yield [3, 4, 7];
	}

	/**
	 * @return PromiseInterface<null>
	 */
	#[DataProviderAttribute("dataProviderGenerator")]
	public function testDataProviderGenerator(int $a, int $b, int $expected) : PromiseInterface {
		$this->assertEquals($expected, $a + $b);

		return resolve(null);
	}

	/**
	 * @return Iterator<int[]>
	 */
	public function dataProviderIterator() : Iterator {
		return new ArrayIterator([
			[1, 2, 3],
			[2, 3, 5],
			[3, 4, 7]
		]);
	}

	/**
	 * @return PromiseInterface<null>
	 */
	#[DataProviderAttribute("dataProviderIterator")]
	public function testDataProviderIterator(int $a, int $b, int $expected) : PromiseInterface {
		$this->assertEquals($expected, $a + $b);

		return resolve(null);
	}
}
