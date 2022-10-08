<?php

namespace ImperaZim\EasyWarps\Functions;

use pocketmine\utils\Config;
use ImperaZim\EasyWarps\Loader;

class WarpCreate {
 
 public static function execute($player, $name, $permission) : void {
  $plugin = Loader::getInstance();
  $config = new Config($plugin->getDataFolder() . "warps.yml");
  $data = $plugin->getDataFolder();
  
  $PosX = (int) $player->getPosition()->getX();
  $PosY = (int) $player->getPosition()->getY();
  $PosZ = (int) $player->getPosition()->getZ(); 
  $world = (string) $player->getWorld()->getDisplayName();
  $position = $PosX . ":" . $PosY . ":" . $PosZ . ":" . $world;
  
  if (isset($config->getAll()[$name])) {
   $player->sendMessage("§l§cWARP§r This warp already exists!  Try another name or delete the existing one!");
  }else{ 
   $config = new Config($data . "warps.yml", Config::YAML, [
    "$name" => [
     "coordinates" => "$position", 
     "permission" => "$permission", 
     "form" => [
      "title" => "$name", 
      "icon" => "$permission"
     ]
    ],
   ]);
   $config->save();
   $player->sendMessage("§l§cWARP§r Warp {$name} successfully created!");
  }
 }
 
}