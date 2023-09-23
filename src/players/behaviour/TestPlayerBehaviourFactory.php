<?php

declare(strict_types=1);

namespace ShockedPlot7560\PmmpUnit\players\behaviour;

use ShockedPlot7560\PmmpUnit\players\TestPlayerManager;

final class TestPlayerBehaviourFactory {
	/**
	 * @var string[]|TestPlayerBehaviour[]
	 * @phpstan-var class-string<TestPlayerBehaviour>[]
	 */
	private static array $behaviours = [];

	public static function registerDefaults(TestPlayerManager $plugin) : void {
		self::register($plugin, "testplayer:auto_equip_armor", AutoEquipArmorTestPlayerBehaviour::class);
		self::register($plugin, "testplayer:pvp", PvPTestPlayerBehaviour::class);
	}

	/**
	 * @param string|TestPlayerBehaviour $class
	 *
	 * @phpstan-param class-string<TestPlayerBehaviour> $class
	 */
	public static function register(TestPlayerManager $plugin, string $identifier, string $class) : void {
		self::$behaviours[$identifier] = $class;
		$class::init($plugin);
	}

	/**
	 * @param mixed[] $data
	 *
	 * @phpstan-param array<string, mixed> $data
	 */
	public static function create(string $identifier, array $data) : TestPlayerBehaviour {
		return self::$behaviours[$identifier]::create($data);
	}
}
