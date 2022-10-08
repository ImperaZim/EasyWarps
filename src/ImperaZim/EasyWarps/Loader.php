<?php

namespace ImperaZim\EasyWarps;

use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\plugin\PluginBase;
use ImperaZim\EasyWarps\Commands\WarpCommand;
use ImperaZim\EasyWarps\Commands\WarpsCommand;

class Loader extends PluginBase {

 public static $instance = null;

 public static function getinstance() : Loader {
  return self::$instance;
 }

 public function onEnable() : void {
  self::$instance = $this;
  self::registerCommands();
  self::registerWarpConfig();
 }
 
 public static function registerWarpConfig() {
  new Config(self::$instance->getDataFolder() . "warps.yml"); 
 } 
 
 public static function registerCommands() : void {
  $map = Server::getinstance()->getCommandMap();
		$map->register("EasyWarps", new WarpCommand());
		$map->register("EasyWarps", new WarpsCommand());
 }

}