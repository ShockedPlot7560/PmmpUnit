<?php

namespace ShockedPlot7560\PmmpUnit\tests\normal\attribute;

use ArrayIterator;
use Generator;
use Iterator;
use React\Promise\PromiseInterface;
use function React\Promise\resolve;
use ShockedPlot7560\PmmpUnit\framework\attribute\DataProviderAttribute;
use ShockedPlot7560\PmmpUnit\framework\TestCase;

class DataProviderAttributeTest extends TestCase {
	public function dataProviderArray() : array {
		return [
			[1, 2, 3],
			[2, 3, 5],
			[3, 4, 7]
		];
	}

	#[DataProviderAttribute("dataProviderArray")]
	public function testDataProviderArray(int $a, int $b, int $expected) : PromiseInterface {
		$this->assertEquals($expected, $a + $b);

		return resolve(null);
	}

	public function dataProviderGenerator() : Generator {
		yield [1, 2, 3];
		yield [2, 3, 5];
		yield [3, 4, 7];
	}

	#[DataProviderAttribute("dataProviderGenerator")]
	public function testDataProviderGenerator(int $a, int $b, int $expected) : PromiseInterface {
		$this->assertEquals($expected, $a + $b);

		return resolve(null);
	}

	public function dataProviderIterator() : Iterator {
		return new ArrayIterator([
			[1, 2, 3],
			[2, 3, 5],
			[3, 4, 7]
		]);
	}

	#[DataProviderAttribute("dataProviderIterator")]
	public function testDataProviderIterator(int $a, int $b, int $expected) : PromiseInterface {
		$this->assertEquals($expected, $a + $b);

		return resolve(null);
	}
}
