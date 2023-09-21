<?php

declare(strict_types=1);

namespace ShockedPlot7560\UnitTest\players;

use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\Packet;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializerContext;
use pocketmine\player\Player;

final class TestPlayerCommandExecutor implements CommandExecutor {
	public function __construct(
		private TestPlayerManager $plugin
	) {
	}

	private function sendServerPacket(Player $sender, Packet $packet) : void {
		$context = new PacketSerializerContext(TypeConverter::getInstance()->getItemTypeDictionary());
		$serializer = PacketSerializer::encoder($context);
		$packet->encode($serializer);
		$sender->getNetworkSession()->handleDataPacket($packet, $serializer->getBuffer());
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool {
		return true;
	}
}
