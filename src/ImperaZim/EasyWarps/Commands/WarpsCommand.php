<?php

use pocketmine\utils\Config;
use pocketmine\player\Player;
use ImperaZim\EasyWarps\Loader;

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
 
 public function getOwningPlugin(): Loader {
		return Loader::getInstance();
	}  

}
