<?php

namespace ImperaZim\EasyWarps\Commands;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\command\Command;
use ImperaZim\EasyWarps\Loader;
use pocketmine\plugin\PluginOwned;
use pocketmine\command\CommandSender;
use ImperaZim\EasyWarps\Functions\WarpCreate;
use ImperaZim\EasyWarps\Functions\WarpDelete;

class WarpCommand extends Command implements PluginOwned {

 public function __construct() {
  parent::__construct("warp", "§7Warp's command!", null, []);
		$this->setPermission("warp.command"); 
 }
 
 public function execute(CommandSender $player, string $commandLabel, array $args) : bool {
  $plugin = $this->getOwningPlugin();
  if (!$player instanceof Player) {
   $plugin->getLogger()->warning("This command is disabled in the console!  Try using it in-game!");
   return true;
  }
  if (isset($args[0])) {
   switch ($args[0]) {
    case 'create': case 'criar':
     if ($player->hasPermission("easywarps.operator.command")) {
      if (!isset($args[1])) {
       $player->sendMessage("§l§cWARP§r You need to enter the warp name!  Use /warp create(name)");
       return true;
      }
      $name = $args[1];
      $permission = isset($args[2]) ? $args[2] : null;
      WarpCreate::execute($player, $name, $permission);
     }else{
      $player->sendMessage("§l§cWARP§r You are not allowed to create a warp!");
      return true;
     }
     break;
    case 'delete': case 'deletar':
     if ($player->hasPermission("easywarps.operator.command")) {
      if (!isset($args[1])) {
       $player->sendMessage("§l§cWARP§r You need to enter the warp name!  Use /warp delete(name)");
       return true;
      }
      $name = $args[1];
      WarpDelete::execute($player, $name); 
     }else{
      $player->sendMessage("§l§cWARP§r You are not allowed to delete a warp!");
      return true;
     }
     break;
   }
  }else{
   $player->sendMessage("§l§cWARP§r Invalid argument!  Use /warp (create, delete)");
  }
  return true;
 }
 
 public function getOwningPlugin(): Loader {
		return Loader::getInstance();
	}  
 
}