<?php

namespace ImperaZim\EasyWarps;

use pocketmine\plugin\PluginBase;

class Loader extends PluginBase {
  
  public static $instance = null;
  
  public static function getinstance() : Loader {
    return self::$instance;
  } 
  
  public function onEnable() : void {
    self::$instance = $this;
  }
  
  public function loadWorlds(){

$levelNamesArray = scandir($this->getServer()->getDataPath() . "worlds/");

  foreach($levelNamesArray as $levelName){

   if($levelName === "." || $levelName === ".."){continue;}

   $this->getServer()->getWorldManager()->loadWorld($levelName);

  } $levels = $this->getServer()->getWorldManager()->getWorlds(); 

 }   
  
} me);
  } $levels = $this->getServer()->getWorldManager()->getWorlds(); 
 }  
