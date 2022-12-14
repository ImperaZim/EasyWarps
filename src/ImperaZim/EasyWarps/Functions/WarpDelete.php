<?php

namespace ImperaZim\EasyWarps\Functions;

use pocketmine\utils\Config;
use ImperaZim\EasyWarps\Loader;

class WarpDelete {
 
 public static function execute($player, $name) : void {
  $plugin = Loader::getInstance();
  $config = new Config($plugin->getDataFolder() . "warps.yml");
  $data = $plugin->getDataFolder();
  
  if (!isset($config->getAll()[$name])) {
   $player->sendMessage("§l§cWARP§r This warp does not exist!");
  }else{ 
   unset($config->getAll()[$name]);
   $config->setAll($config->getAll()); 
   $config->save(); 
   $player->sendMessage("§l§cWARP§r Warp {$name} successfully deleted!");
  }
 }
 
}