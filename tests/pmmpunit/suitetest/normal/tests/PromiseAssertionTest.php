<?php

namespace ShockedPlot7560\PmmpUnit\tests\normal;

use AssertionError;
use Exception;
use InvalidArgumentException;
use pocketmine\scheduler\ClosureTask;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use RuntimeException;
use ShockedPlot7560\PmmpUnit\framework\attribute\ExpectedExceptionAttribute;
use ShockedPlot7560\PmmpUnit\framework\TestCase;
use ShockedPlot7560\PmmpUnit\PmmpUnit;

class PromiseAssertionTest extends TestCase {
	/**
	 * @phpstan-return PromiseInterface<void>
	 */
	public function testPromiseRejects() : PromiseInterface {
		$deferred = new Deferred();
		PmmpUnit::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($deferred) : void {
			$deferred->reject(new Exception());
		}), 1);

		return $this->assertPromiseRejects($deferred->promise());
	}

	/**
	 * @phpstan-return PromiseInterface<void>
	 */
	#[ExpectedExceptionAttribute(AssertionError::class)]
	public function testPromiseRejectionButResolved() : PromiseInterface {
		$deferred = new Deferred();
		PmmpUnit::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($deferred) : void {
			$deferred->resolve(true);
		}), 1);

		return $this->assertPromiseRejects($deferred->promise());
	}

	/**
	 * @phpstan-return PromiseInterface<void>
	 */
	public function testPromiseRejectionWith() : PromiseInterface {
		$deferred = new Deferred();
		PmmpUnit::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($deferred) : void {
			$deferred->reject(new Exception());
		}), 1);

		return $this->assertPromiseRejectsWith($deferred->promise(), Exception::class);
	}

	/**
	 * @phpstan-return PromiseInterface<void>
	 */
	#[ExpectedExceptionAttribute(InvalidArgumentException::class)]
	public function testPromiseRejectionWithAnotherClass() : PromiseInterface {
		$deferred = new Deferred();
		PmmpUnit::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($deferred) : void {
			$deferred->reject(new Exception('test'));
		}), 1);

		return $this->assertPromiseRejectsWith($deferred->promise(), RuntimeException::class);
	}

	/**
	 * @phpstan-return PromiseInterface<void>
	 */
	public function testPromiseRejectionWithMessage() : PromiseInterface {
		$deferred = new Deferred();
		PmmpUnit::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($deferred) : void {
			$deferred->reject(new Exception('test'));
		}), 1);

		return $this->assertPromiseRejectsWithMessage($deferred->promise(), 'test');
	}

	/**
	 * @phpstan-return PromiseInterface<void>
	 */
	#[ExpectedExceptionAttribute(InvalidArgumentException::class)]
	public function testPromiseRejectionWithAnotherMessage() : PromiseInterface {
		$deferred = new Deferred();
		PmmpUnit::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($deferred) : void {
			$deferred->reject(new Exception('test2'));
		}), 1);

		return $this->assertPromiseRejectsWithMessage($deferred->promise(), 'test');
	}

	/**
	 * @phpstan-return PromiseInterface<void>
	 */
	public function testPromiseRejectionWithMessageThatContains() : PromiseInterface {
		$deferred = new Deferred();
		PmmpUnit::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($deferred) : void {
			$deferred->reject(new Exception('test'));
		}), 1);

		return $this->assertPromiseRejectsWithMessageThatContains($deferred->promise(), 'es');
	}

	/**
	 * @phpstan-return PromiseInterface<void>
	 */
	#[ExpectedExceptionAttribute(InvalidArgumentException::class)]
	public function testPromiseRejectionWithMessageThatDoesNotContain() : PromiseInterface {
		$deferred = new Deferred();
		PmmpUnit::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($deferred) : void {
			$deferred->reject(new Exception('test'));
		}), 1);

		return $this->assertPromiseRejectsWithMessageThatContains($deferred->promise(), 'es2');
	}
}
