<?php

use pocketmine\utils\Config;
use pocketmine\player\Player;
use pocketmine\world\Position;
use pocketmine\command\Command;
use ImperaZim\EasyWarps\Loader;
use pocketmine\plugin\PluginOwned;
use pocketmine\command\CommandSender;

class WarpsCommand extends Command implements PluginOwned {
 
 public function __construct() {
  parent::__construct("warps", "§7Lista de warps!", null, []);
		$this->setPermission("warps.command"); 
 }
 
 public function execute(CommandSender $player, string $commandLabel, array $args) : bool {
  $plugin = $this->getOwningPlugin(); 
  if (!$player instanceof Player) {
   $plugin->getLogger()->warning("Este comando está desabilitado no console! Tente usa-lo dentro do jogo!");
   return true;
  }
  //send Menu
  return true;
 }
 
 public static function Warps($player) {
  $form = FormAPI::createSimpleForm(function($player, $data = null) {
   $config = (new Config(self::getPlugin()->getDataFolder() . "warps.yml"))->getAll(); 
   
   if($data == "back") { return true; }
   if($data != null){
    $coord = explode(":", $config[$data]["coordinates"]);
    $x = $coord[0]; $y = $coord[1]; $z = $coord[2];
    $world = Server::getInstance()->getWorldManager()->getWorldByName($coord[3]);
    Server::getInstance()->getWorldManager()->loadWorld($coord[3]); 
    $player->teleport(new Position($x, $y, $z, $world));
    $player->sendTitle("§e" . $config["form"]["title"]);
   }
  });
  
  $config = new Config(self::getPlugin()->getDataFolder() . "warps.yml"); 
  $config = $config->getAll();
  
  $form->setTitle("§eLista de Warps");
  $form->setContent("§8Click para teleportar!");
  $form->addButton("§cFechar", 0, "", "back");
  foreach ($config as $data){
   $form->addButton(data["form"]["title"], 0, data["form"]["icon"], data["form"]["title"]);
  }
  $form->sendToPlayer($player);
  return $form;  
 }
 
 public function getOwningPlugin(): Loader {
		return Loader::getInstance();
	}  

}
