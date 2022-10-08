<?php

namespace ImperaZim\EasyWarps\Commands;

use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\player\Player;
use pocketmine\world\Position;
use pocketmine\command\Command;
use ImperaZim\EasyWarps\Loader;
use pocketmine\plugin\PluginOwned;
use pocketmine\command\CommandSender;
use ImperaZim\EasyWarps\Utils\Form\FormAPI;

class WarpsCommand extends Command implements PluginOwned {
 
 public function __construct() {
  parent::__construct("warps", "§7Warp's menu!", null, []);
		$this->setPermission("easywarps.default.command"); 
 }
 
 public function execute(CommandSender $player, string $commandLabel, array $args) : bool {
  $plugin = $this->getOwningPlugin(); 
  if (!$player instanceof Player) {
   $plugin->getLogger()->warning("This command is disabled in the console!  Try using it in-game!");
   return true;
  }
  self::Warps($player);
  return true;
 }
 
 public static function Warps($player) {
  $form = FormAPI::createSimpleForm(function($player, $data = null) {
   $config = (new Config(Loader::getInstance()->getDataFolder() . "warps.yml"))->getAll(); 
   
   if($data == "back") { return true; }
   if($data != null){
    $coord = explode(":", $config[$data]["coordinates"]);
    $x = $coord[0]; $y = $coord[1]; $z = $coord[2];
    if(!in_array($coord[3], Server::getInstance()->getWorldManager()->getWorlds())){
      $player->sendMessage("§l§cWARP§r Unable to teleport to warp because warp is in a corrupted or unloaded world!"); 
     return true;
    }
    $world = Server::getInstance()->getWorldManager()->getWorldByName($coord[3]);
    Server::getInstance()->getWorldManager()->loadWorld($coord[3]); 
    if(!$player->hasPermission($config[$data]["permission"])) {
     $player->sendMessage("§l§cWARP§r Você não tem permissão para teleportar para essa warp!"); 
     return true;
    }
    $player->teleport(new Position($x, $y, $z, $world));
    $player->sendTitle("§e" . $config[$data]["form"]["title"]);
   }
  });
  
  $config = new Config(Loader::getInstance()->getDataFolder() . "warps.yml"); 
  $config = $config->getAll();
  
  $form->setTitle("§eLista de Warps");
  $form->setContent("§8Click para teleportar!");
  $form->addButton("§cFechar", 0, "", "back");
  foreach ($config as $data){
   $form->addButton($config["form"]["title"], 0, $config["form"]["icon"], $config["form"]["title"]);
  }
  $form->sendToPlayer($player);
  return $form;  
 }
 
 public function getOwningPlugin() : Loader {
		return Loader::getInstance();
	}  

}
