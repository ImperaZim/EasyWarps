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
  parent::__construct("warp", "§7Warp command!", null, []);
		$this->setPermission("warp.command"); 
 }
 
 public function execute(CommandSender $player, string $commandLabel, array $args) : bool {
  $plugin = $this->getOwningPlugin();
  if (isset($args[0])) {
   switch ($args[0]) {
    case 'create': case 'criar':
     if ($player->hasPermission("warp.create")) {
      if (!isset($args[1])) {
       $player->sendMessage("§l§cWARP§r Você precisa digitar o nome da warp! Use /warp create (name)!");
       return true;
      }
      $name = $args[1];
      $permission = isset($args[2]) ? $args[2] : "";
      WarpCreate::execute($player, $name, $permission);
     }else{
      $player->sendMessage("§l§cWARP§r Você não tem permissão para criar uma warp!");
      return true;
     }
     break;
    case 'delete': case 'deletar':
     if ($player->hasPermission("warp.delete")) {
      if (!isset($args[1])) {
       $player->sendMessage("§l§cWARP§r Você precisa digitar o nome da warp! Use /warp delete (name)!");
       return true;
      }
      $name = $args[1];
      WarpDelete::execute($player, $name); 
     }else{
      $player->sendMessage("§l§cWARP§r Você não tem permissão para deletar uma warp!");
      return true;
     }
     break;
   }
  }else{
   $player->sendMessage("§l§cWARP§r Argumento invalido! Use /warp (criar, deletar)!");
  }
 }
 
 public function getOwningPlugin(): Loader {
		return Loader::getInstance();
	}  
 
}