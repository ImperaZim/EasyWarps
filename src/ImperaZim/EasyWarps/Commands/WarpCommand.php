<?php

namespace ImperaZim\EasyWarps\Commands;

use ImperaZim\EasyWarps\Loader;
use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\plugin\PluginOwned;
use pocketmine\command\CommandSender;

class WarpCommand extends Command implements PluginOwned {

 public function __construct() {
  parent::__construct("warp", "§7Warp command!", null, []);
		$this->setPermission("warp.command"); 
 }
 
 public function execute(CommandSender $player, string $commandLabel, array $args) : bool {
  $plugin = $this->getOwningPlugin();
 }
 
 public function getOwningPlugin(): Loader {
		return Loader::getInstance();
	}  
 
}