<?php

namespace ImperaZim\EasyWarps;

use pocketmine\Server;
use pocketmine\plugin\PluginBase;

class Loader extends PluginBase {
  
  public static $instance = null;
  
  public static function getinstance() : Loader {
    return self::$instance;
  } 
  
  public function onEnable() : void {
    self::$instance = $this;
    self::registerWorlds();
  }

  public static function registerWorlds() : void {
  $worlds = scandir(->getDataPath() . "worlds/");
  foreach($worlds as $world){
   Server::getinstance()->getWorldManager()->loadWorld($world);
  } 
 }   
  
}  
