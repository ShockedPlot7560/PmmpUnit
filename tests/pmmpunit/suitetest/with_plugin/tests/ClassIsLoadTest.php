<?php

namespace ShockedPlot7560\PmmpUnit\tests\withPlugin;

use antbag\chatgames\Main;
use React\Promise\PromiseInterface;
use function React\Promise\resolve;
use ShockedPlot7560\PmmpUnit\framework\TestCase;

class ClassIsLoadTest extends TestCase {
	public function testChatGamesMainClassIsLoad() : PromiseInterface {
		$this->assertTrue(class_exists(Main::class));

		return resolve(null);
	}
}
