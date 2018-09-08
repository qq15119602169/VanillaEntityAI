<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\hostile;

use jasonwynn10\VanillaEntityAI\inventory\MobInventory;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;
use pocketmine\Player;

class Husk extends Zombie {
	public const NETWORK_ID = self::HUSK;

	public $width = 1.031;
	public $height = 2.0;

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
		//TODO chance drop item and armour
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
		return "Husk";
	}

	/**
	 * @return MobInventory
	 */
	public function getInventory() {
		return $this->inventory;
	}

	protected function sendSpawnPacket(Player $player) : void {
		parent::sendSpawnPacket($player);

		$pk = new MobEquipmentPacket();
		$pk->entityRuntimeId = $this->getId();
		$pk->item = $this->inventory->getItemInHand();
		$pk->inventorySlot = $pk->hotbarSlot = $this->inventory->getHeldItemIndex();
		$player->dataPacket($pk);
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
}