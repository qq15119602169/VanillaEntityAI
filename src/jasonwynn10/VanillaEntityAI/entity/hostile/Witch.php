<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\hostile;

use jasonwynn10\VanillaEntityAI\inventory\MobInventory;
use pocketmine\entity\Monster;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;
use pocketmine\Player;

class Witch extends Monster {
	public const NETWORK_ID = self::WITCH;

	public $width = 0.6;
	public $height = 1.95;

	/** @var MobInventory */
	private $inventory;

	public function initEntity() : void {
		$this->inventory = new MobInventory($this, ItemFactory::get(Item::AIR));
		parent::initEntity();
	}

	/**
	 * @param int $tickDiff
	 *
	 * @return bool
	 */
	public function entityBaseTick(int $tickDiff = 1) : bool {
		return parent::entityBaseTick($tickDiff); // TODO: Change the autogenerated stub
	}

	/**
	 * @return array
	 */
	public function getDrops() : array {
		$drops = parent::getDrops();
		//TODO chance drop potion
		return $drops;
	}

	/**
	 * @return int
	 */
	public function getXpDropAmount() : int {
		//TODO: check for equipment and whether it's a baby
		return 5;
	}

	/**
	 * @return string
	 */
	public function getName() : string {
		return "Witch";
	}

	/**
	 * @return MobInventory
	 */
	public function getInventory() {
		return $this->inventory;
	}

	public function close() : void {
		if(!$this->closed) {
			if($this->inventory !== null) {
				$this->inventory->removeAllViewers(true);
				$this->inventory = null;
			}
			parent::close();
		}
	}

	protected function sendSpawnPacket(Player $player) : void {
		parent::sendSpawnPacket($player);

		$pk = new MobEquipmentPacket();
		$pk->entityRuntimeId = $this->getId();
		$pk->item = $this->inventory->getItemInHand();
		$pk->inventorySlot = $pk->hotbarSlot = $this->inventory->getHeldItemIndex();
		$player->dataPacket($pk);
	}
}