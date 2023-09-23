<?php

declare(strict_types=1);

namespace ShockedPlot7560\PmmpUnit\players\info;

use pocketmine\entity\Skin;
use Ramsey\Uuid\UuidInterface;

final class TestPlayerInfo {
	/**
	 * @param array<string, mixed> $extra_data
	 * @param array<string, array<string, mixed>> $behaviours
	 */
	public function __construct(
		public UuidInterface $uuid,
		public string $xuid,
		public string $username,
		public Skin $skin,
		public array $extra_data,
		public array $behaviours
	) {
	}
}
