<?php

declare(strict_types=1);

namespace ShockedPlot7560\UnitTest\players\listener;

use Closure;
use pocketmine\player\Player;
use pocketmine\utils\Utils;

final class ClosureTestPlayerListener implements TestPlayerListener {
	private Closure $onPlayerAdd;
	private Closure $onPlayerRemove;

	public function __construct(Closure $onPlayerAdd, Closure $onPlayerRemove) {
		Utils::validateCallableSignature(static function (Player $player) : void {}, $onPlayerAdd);
		$this->onPlayerAdd = $onPlayerAdd;

		Utils::validateCallableSignature(static function (Player $player) : void {}, $onPlayerRemove);
		$this->onPlayerRemove = $onPlayerRemove;
	}

	public function onPlayerAdd(Player $player) : void {
		($this->onPlayerAdd)($player);
	}

	public function onPlayerRemove(Player $player) : void {
		($this->onPlayerRemove)($player);
	}
}
