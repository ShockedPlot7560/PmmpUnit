<?php

declare(strict_types=1);

namespace ShockedPlot7560\PmmpUnit\players\behaviour;

use pocketmine\event\entity\EntityItemPickupEvent;
use pocketmine\event\EventPriority;
use pocketmine\item\Armor;
use pocketmine\player\Player;
use ShockedPlot7560\PmmpUnit\players\TestPlayer;
use ShockedPlot7560\PmmpUnit\players\TestPlayerManager;

final class AutoEquipArmorTestPlayerBehaviour implements TestPlayerBehaviour {
	private const METADATA_KEY = "behaviour:auto_equip_armor";

	public static function create(array $data) : self {
		return new self();
	}

	public static function init(TestPlayerManager $plugin) : void {
		$plugin->getPlugin()->getServer()->getPluginManager()->registerEvent(EntityItemPickupEvent::class, static function (EntityItemPickupEvent $event) use ($plugin) : void {
			$item = $event->getItem();
			if (!($item instanceof Armor)) {
				return;
			}

			$entity = $event->getEntity();
			if (!($entity instanceof Player)) {
				return;
			}

			$testPlayer = $plugin->getTestPlayer($entity);
			if ($testPlayer === null || $testPlayer->getMetadata(self::METADATA_KEY) === null) {
				return;
			}

			if ($event->getInventory() !== $entity->getInventory()) {
				return;
			}

			$destination_inventory = $entity->getArmorInventory();
			$destination_slot = $item->getArmorSlot();
			if (!$destination_inventory->getItem($destination_slot)->isNull()) {
				return;
			}

			($ev = new EntityItemPickupEvent($entity, $event->getOrigin(), $item, $destination_inventory))->call();
			if ($ev->isCancelled()) {
				return;
			}

			$event->cancel();
			$event->getOrigin()->flagForDespawn();
			$destination_inventory->setItem($destination_slot, $item);
		}, EventPriority::NORMAL, $plugin->getPlugin());
	}

	public function __construct() {
	}

	public function onAddToPlayer(TestPlayer $player) : void {
		$player->setMetadata(self::METADATA_KEY, true);
	}

	public function onRemoveFromPlayer(TestPlayer $player) : void {
		$player->deleteMetadata(self::METADATA_KEY);
	}

	public function tick(TestPlayer $player) : void {
	}

	public function onRespawn(TestPlayer $player) : void {
	}
}
