<?php

namespace QuiverArrow\EnesTNTm;

//PocketMine Event Class
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerRespawnEvent; 
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerToggleFlightEvent;
use pocketmine\event\inventory\InventoryPickupItemEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\ExplosionPrimeEvent;
use pocketmine\event\Listener;
use pocketmine\event\inventory\InventoryOpenEvent;
use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\entity\EntityShootBowEvent;
use pocketmine\event\entity\EntityArmorChangeEvent;

//PocketMine Evebody Class
use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\item\Armor;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\utils\Color;
use pocketmine\utils\Config;
use pocketmine\entity\Living;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\CommandExecutor;

//Plugin Class
use _64FF00\PurePerms\PPGroup;
use onebone\economyapi\EconomyAPI;
use jojoe77777\FormAPI;



/*
*
* Plugin Data is Have a Rafalski Desimo (Enes Keskin)
*
* Bu yazılım Yeni Miras sunucusunun skywars ayrıntısına öznitelik olarak yapılmıştır. bu sebeple lütfen yazılımı paylaşmayınız. bu yazılım size verildiyse yazılımı kullanınız ancak paylaşılması söz konusu olamaz
*
* NowaLegacy Sponsor center Yeni Miras (2020)
* Bu sunucu NowaLegacy sponsorluğu içerisinde kurulmuştur. 2015 - 2020 (NowaLegacy) / 2020 YeniMiras
*/


//Listener PluginBase extends

class Main extends PluginBase implements Listener {
	
	//Config
	public $config;
	
	public $sconfig;
	
	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->info("Quiver Arrow is Activated");
		@mkdir($this->getDataFolder());
		if(!file_exists($this->getDataFolder() . "config.yml")){
		$this->saveResource("config.yml");
		$this->sconfig = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		@mkdir($this->getDataFolder() . "Player/");
		}
	}
	
	public function onDisable() {
		$this->getLogger()->info("Quiver Arrow is Disabled");
	}


	public function onJoin(PlayerJoinEvent $e){
	$p = $e->getPlayer();
	$this->sconfig = new Config($this->getDataFolder() . "config.yml", Config::YAML);
	if($this->sconfig->get("unlimited") == false){
	$p->getInventory()->setItem(8, Item::get(399, 0, 1)->setCustomName("§7Quiver"));
	}
	}

		public function onShoot(EntityShootBowEvent $event){
			if($event->getEntity() instanceof Player){
				$entity = $event->getEntity();
				$in = $event->getEntity()->getInventory()->getItemInHand()->getId();
				$this->sconfig = new Config($this->getDataFolder() . "config.yml", Config::YAML);
				if($this->sconfig->get("unlimited") == false){
			if($in == 261){
				if($this->okDerle($entity, true) == true){
					$entity->getInventory()->setItem(8, Item::get(262, 0, 1)->setCustomName("§7Quiver An Arrow"));
				}else{
					$entity->getInventory()->setItem(8, Item::get(399, 0, 1)->setCustomName("§7Quiver"));
				}
			}else{
					$entity->getInventory()->setItem(8, Item::get(399, 0, 1)->setCustomName("§7Quiver"));
				}
			}elseif($this->sconfig->get("unlimited") == true && $in == 261){
				$entity->getInventory()->setItem(8, Item::get(262, 0, 1)->setCustomName("Quiver An Arrow"));
			}else{
				$entity->getInventory()->setItem(8, Item::get(0, 0, 0));
			}
		}
	}

		public function onInteract(PlayerInteractEvent $event) {
		
		$in = $event->getPlayer()->getInventory()->getItemInHand()->getCustomName();
		
		if($in == "§7Quiver"){
			
			$this->okForm($event->getPlayer());
			
		}
		
	}

		public function okDerle(Player $player, bool $harca = false){
			$this->sconfig = new Config($this->getDataFolder() . "config.yml", Config::YAML);
			$this->config = new Config($this->getDataFolder() . "Player/" . $player->getName() . ".yml", Config::YAML);
			if(!$this->config->get("Ok") || $this->config->get("Ok") == 0){
				return false;
			}else{
				if($harca == true){
					$this->config->set("Ok", $this->config->get("Ok") - 1);
					$this->config->save();
					if($this->config->get("Ok") == 60){
						$player->sendMessage($this->sconfig->get("lowarrow"));
					}elseif($this->config->get("Ok") == 0){
						$player->sendMessage($this->sconfig->get("arrownomore"));
					}
				}
				return true;
			}
		}
		
		
		public function onChanceSlot(PlayerItemHeldEvent $event){
			$player = $event->getPlayer();
			$slot = $event->getSlot();
			$in = $event->getPlayer()->getInventory()->getItem($slot)->getId();
			$this->sconfig = new Config($this->getDataFolder() . "config.yml", Config::YAML);
				if($this->sconfig->get("unlimited") == false){
			if($in == 261){
				if($this->okDerle($player, false) == true){
					$player->getInventory()->setItem(8, Item::get(262, 0, 1)->setCustomName("Quiver An Arrow"));
				}else{
					$player->getInventory()->setItem(8, Item::get(399, 0, 1)->setCustomName("§7Quiver"));
				}
			}else{
					$player->getInventory()->setItem(8, Item::get(399, 0, 1)->setCustomName("§7Quiver"));
				}
			}elseif($this->sconfig->get("unlimited") == true && $in == 261){
				$player->getInventory()->setItem(8, Item::get(262, 0, 1)->setCustomName("Quiver An Arrow"));
			}else{
				$player->getInventory()->setItem(8, Item::get(0, 0, 0));
			}
		}
	
	public function okForm(Player $o){
		$f = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createSimpleForm(function (Player $o, array $data){
			$re = $data[0];
			$bal = (int) EconomyAPI::getInstance()->myMoney($o);
			if($re === null){
				return true;
			}
			switch ($re){
				case 0:
				if($bal > 60 * $this->sconfig->get("arrowprice")){
					$this->sconfig = new Config($this->getDataFolder() . "config.yml", Config::YAML);
					$this->config = new Config($this->getDataFolder() . "Player/" . $o->getName() . ".yml", Config::YAML);
					if($this->sconfig->get("maxarrow") > 60 + $this->config->get("Ok")){
					EconomyAPI::getInstance()->reduceMoney($o, 60 * $this->sconfig->get("arrowprice"));
					$this->config->set("Ok", $this->config->get("Ok") + 60);
					$this->config->save();
					$ok = $this->config->get("Ok");
					$o->sendMessage($this->sconfig->get("buysuccess"));
					}else{
					$o->sendMessage($this->sconfig->get("arrowmaxed"));
					}
				}else{
					$o->sendMessage($this->sconfig->get("buyfailed"));
				}
				break;
				case 1:
				break;
			}
		});
		$this->sconfig = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		$this->config = new Config($this->getDataFolder() . "Player/" . $o->getName() . ".yml", Config::YAML);
		$ok = $this->config->get("Ok");
		
		$f->setTitle($this->sconfig->get("menutitle"));
		$f->setContent("§7Arrows:\n§6$ok\n" . $this->sconfig->get("menucontent"));
		$f->addButton("§7+60" . $this->sconfig->get("buybutton"));
		$f->addButton($this->sconfig->get("exitbutton"));
		$f->sendToPlayer($o);
	}
	
	














	
}






?>