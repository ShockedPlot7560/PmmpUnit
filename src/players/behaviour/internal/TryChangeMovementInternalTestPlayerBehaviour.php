<?php

declare(strict_types=1);

namespace ShockedPlot7560\UnitTest\players\behaviour\internal;

use ShockedPlot7560\UnitTest\players\behaviour\TestPlayerBehaviour;
use ShockedPlot7560\UnitTest\players\TestPlayer;
use ShockedPlot7560\UnitTest\players\TestPlayerManager;
use ShockedPlot7560\UnitTest\players\network\listener\ClosureTestPlayerPacketListener;
use ShockedPlot7560\UnitTest\players\network\listener\TestPlayerPacketListener;
use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\ClientboundPacket;
use pocketmine\network\mcpe\protocol\SetActorMotionPacket;
use pocketmine\player\Player;
use ReflectionMethod;
use ReflectionProperty;
use RuntimeException;

final class TryChangeMovementInternalTestPlayerBehaviour implements TestPlayerBehaviour {
	use InternalTestPlayerBehaviourTrait;

	public static function init(TestPlayerManager $plugin) : void {
	}

	private static function changeDrag(Player $player) : void {
		static $_drag = null;
		if ($_drag === null) {
			/** @see Player::$drag */
			$_drag = new ReflectionProperty(Entity::class, "drag");
			$_drag->setAccessible(true);
		}

		$_drag->setValue($player, $_drag->getValue($player) * 8);
	}

	private static function writeMovementToPlayer(Player $player, Vector3 $motion) : void {
		static $_motion = null;
		if ($_motion === null) {
			/** @see Player::$motion */
			$_motion = new ReflectionProperty(Entity::class, "motion");
			$_motion->setAccessible(true);
		}

		$_motion->setValue($player, $motion->asVector3());
	}

	private static function tryChangeMovement(Player $player) : void {
		static $reflection_method = null;
		if ($reflection_method === null) {
			/** @see Human::tryChangeMovement() */
			$reflection_method = new ReflectionMethod(Human::class, "tryChangeMovement");
			$reflection_method->setAccessible(true);
		}
		$reflection_method->getClosure($player)();
	}
	private ?TestPlayerPacketListener $motion_packet_listener = null;

	public function __construct(
		private TestPlayerMovementData $data
	) {
	}

	public function onAddToPlayer(TestPlayer $player) : void {
		if ($this->motion_packet_listener !== null) {
			throw new RuntimeException("Listener was already added");
		}

		$player_instance = $player->getPlayer();
		$player_instance->keepMovement = false;
		self::changeDrag($player_instance);

		$player_id = $player_instance->getId();
		$this->motion_packet_listener = new ClosureTestPlayerPacketListener(function (ClientboundPacket $packet, NetworkSession $session) use ($player_id) : void {
			/** @var SetActorMotionPacket $packet */
			if ($packet->actorRuntimeId === $player_id) {
				$this->data->motion = $packet->motion->asVector3();
			}
		});
		$player->getNetworkSession()->registerSpecificPacketListener(SetActorMotionPacket::class, $this->motion_packet_listener);
	}

	public function onRemoveFromPlayer(TestPlayer $player) : void {
		$player->getNetworkSession()->unregisterSpecificPacketListener(
			SetActorMotionPacket::class,
			$this->motion_packet_listener ?? throw new RuntimeException("Listener was already removed")
		);
		$this->motion_packet_listener = null;
	}

	public function tick(TestPlayer $player) : void {
		$player_instance = $player->getPlayer();
		self::writeMovementToPlayer($player_instance, $this->data->motion);
		self::tryChangeMovement($player_instance);
	}

	public function onRespawn(TestPlayer $player) : void {
	}
}
