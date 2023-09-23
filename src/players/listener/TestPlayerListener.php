<?php

declare(strict_types=1);

namespace ShockedPlot7560\PmmpUnit\players\listener;

use pocketmine\player\Player;

interface TestPlayerListener {
	public function onPlayerAdd(Player $player) : void;

	public function onPlayerRemove(Player $player) : void;
}
