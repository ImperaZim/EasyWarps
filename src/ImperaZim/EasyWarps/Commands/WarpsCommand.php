<?php

class WarpsCommand extends Command implements PluginOwned {
 
 public function __construct() {
  parent::__construct("warps", "§7Lista de warps!", null, []);
		$this->setPermission("warp.command"); 
 }
 
 public function execute(CommandSender $player, string $commandLabel, array $args) : bool {
  $plugin = $this->getOwningPlugin(); 
  
  return true;
 }
 
 public function getOwningPlugin(): Loader {
		return Loader::getInstance();
	}  

}
