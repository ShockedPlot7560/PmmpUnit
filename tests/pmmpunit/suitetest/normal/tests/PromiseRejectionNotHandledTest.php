<?php

namespace ShockedPlot7560\PmmpUnit\tests\normal;

use Exception;
use React\Promise\PromiseInterface;
use function React\Promise\reject;
use ShockedPlot7560\PmmpUnit\framework\TestCase;

class PromiseRejectionNotHandledTest extends TestCase {
    /** @phpstan-return PromiseInterface<null> */
	public function testPromiseRejectedToException() : PromiseInterface {
		$this->expectException(Exception::class);

		return reject(new Exception("Promise rejected"));
	}
}
