<?php

declare(strict_types=1);

namespace ShockedPlot7560\PmmpUnit\players\util;

/**
 * @phpstan-template TKey of string|int
 * @phpstan-template TVal of mixed
 */
final class SortedMap {
	/**
	 * @var array<string|int, mixed>
	 * @phpstan-var array<TKey, TVal>
	 */
	private array $entries = [];

	/**
	 * @var array<string|int, int>
	 * @phpstan-var array<TKey, int>
	 */
	private array $key_scores = [];

	public function __construct() {
	}

	/**
	 * @phpstan-param TKey $key
	 */
	public function contains(string|int $key) : bool {
		return isset($this->entries[$key]);
	}

	/**
	 * @phpstan-param TKey $key
	 * @phpstan-param TVal $value
	 */
	public function set(string|int $key, mixed $value, int $score) : void {
		$this->entries[$key] = $value;
		$this->key_scores[$key] = $score;
        $scores = $this->key_scores;
		uksort($scores, fn (string $k1, string $k2) : int => $this->key_scores[$k1] <=> $this->key_scores[$k2]);
        $this->key_scores = $scores;
	}

	/**
	 * @phpstan-param TKey $key
	 */
	public function remove(string|int $key) : void {
		unset($this->entries[$key], $this->key_scores[$key]);
	}

	/**
	 * @return mixed[]
	 *
	 * @phpstan-return array<TKey, TVal>
	 */
	public function getAll() : array {
		return $this->entries;
	}
}
