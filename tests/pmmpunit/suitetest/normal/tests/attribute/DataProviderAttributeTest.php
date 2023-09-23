<?php

namespace ShockedPlot7560\PmmpUnit\tests\normal\attribute;

use React\Promise\PromiseInterface;
use function React\Promise\resolve;
use ShockedPlot7560\PmmpUnit\framework\attribute\DataProviderAttribute;
use ShockedPlot7560\PmmpUnit\framework\TestCase;

class DataProviderAttributeTest extends TestCase {
	public function dataProvider() : array {
		return [
			[1, 2, 3],
			[2, 3, 5],
			[3, 4, 7]
		];
	}

	#[DataProviderAttribute("dataProvider")]
	public function testExternalDataProvider(int $a, int $b, int $expected) : PromiseInterface {
		$this->assertEquals($expected, $a + $b);

		return resolve(null);
	}
}
