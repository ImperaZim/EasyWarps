<?php

namespace ImperaZim\EasyWarps\Functions;

use pocketmine\utils\Config;
use ImperaZim\EasyWarps\Loader;

class WarpCreate {
 
 public static function execute($player, $name, $permission = "") : void {
  $plugin = Loader::getInstance();
  $config = new Config($plugin->getDataFolder() . "warps.yml");
  $warps = $config->getAll();
  $data = $plugin->getDataFolder();
  
  $PosX = (int) $player->getPosition()->getX();
  $PosY = (int) $player->getPosition()->getY();
  $PosZ = (int) $player->getPosition()->getZ(); 
  $world = (string) $player->getWorld()->getDisplayName();
  $position = $PosX . ":" . $PosY . ":" . $PosZ . ":" . $world;
  
  if (isset($warps[$name])) {
   //warp has exists
  }else{ 
   $config = new Config($data . "warps.yml", Config::YAML, [
    "$name" => [
     "coordinates" => "$position", 
     "permission" => "$permission", 
     "form" => [
      "title" => "$name", 
      "icon" => ""
     ]
    ],
   ]);
   $config->save();
   //sucess message
  }
 }
 
}