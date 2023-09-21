<?php

namespace ShockedPlot7560\UnitTest\players\listener;

use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ServerboundPacket;
use React\Promise\Deferred;

class PacketReceiveListener implements Listener {
	/** @var array<string, array<string, Deferred<ServerboundPacket>>> $listeners */
	private array $listeners = [];

	/**
	 * @return Deferred<ServerboundPacket>
	 */
	public function addListener(string $playerName, string $packetClass) : Deferred {
		$this->listeners[$playerName][$packetClass] = new Deferred();

		return $this->listeners[$playerName][$packetClass];
	}

	public function removeListener(string $playerName, string $packetClass) : void {
		unset($this->listeners[$playerName][$packetClass]);
	}

	public function onPacketReceive(DataPacketReceiveEvent $event) : void {
		$playerName = $event->getOrigin()->getDisplayName();
		$packet = $event->getPacket();
		if (isset($this->listeners[$playerName][$packet::class])) {
			$listener = $this->listeners[$playerName][$packet::class];
			$listener->resolve($packet);
			unset($this->listeners[$playerName][$packet::class]);
		}
	}
}
