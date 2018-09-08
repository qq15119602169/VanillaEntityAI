<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\hostile;

use jasonwynn10\VanillaEntityAI\inventory\MobInventory;
use pocketmine\entity\Monster;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;
use pocketmine\Player;

class ZombiePigman extends Monster implements \pocketmine\entity\Ageable {
	public const NETWORK_ID = self::ZOMBIE_PIGMAN;

	public $width = 2.0;
	public $height = 2.0;

	/** @var MobInventory */
	private $inventory;

	public function initEntity() : void {
		$this->inventory = new MobInventory($this, ItemFactory::get(Item::GOLD_SWORD));
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
		if($this->isBaby())
			return 12;
		$exp = 5;
		foreach($this->getArmorInventory()->getContents() as $peice)
			$exp += mt_rand(1, 3);
		return $exp;
	}

	public function isBaby() : bool {
		return false; //TODO
	}

	/**
	 * @return string
	 */
	public function getName() : string {
		return "Zombie Pigman";
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