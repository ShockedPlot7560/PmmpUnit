<?php

declare(strict_types=1);

namespace ShockedPlot7560\UnitTest\players\network\listener;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\ClientboundPacket;

interface TestPlayerPacketListener{

	public function onPacketSend(ClientboundPacket $packet, NetworkSession $session) : void;
}