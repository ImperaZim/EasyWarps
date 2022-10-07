<?php

namespace ImperaZim\EasyWarps\Functions;

use pocketmine\utils\Config;
use ImperaZim\EasyWarps\Loader;

class WarpCreate {
 
 public static function execute($player, $name) : void {
  $plugin = Loader::getInstance();
  $config = new Config($plugin->getDataFolder() . "warps.yml");
  $data = $plugin->getDataFolder();
  
  if (!isset($config->getAll()[$name])) {
   $player->sendMessage("§l§cWARP§r Está warp não existe!");
   return true;
  }else{ 
   unset($warps[$name]);
   $config->setAll($config->getAll()); 
   $config->save(); 
   $player->sendMessage("§l§cWARP§r Warp {$name} deletada com sucesso!");
  }
 }
 
}