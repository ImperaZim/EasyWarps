<?php

namespace commands\arguments;

use warp\WarpManager;
use pocketmine\player\Player;
use libraries\commando\BaseSubCommand;

/**
* Class Lista
* @package commands\island\arguments
*/
class Lista extends BaseSubCommand {

  /**
  * Set up the command, including permissions and arguments.
  */
  protected function prepare() : void {
    $this->setPermission('easywarps.admin');
  }

  /**
  * Execute the sub-command logic.
  *
  * @param mixed $player
  * @param string $aliasUsed
  * @param array $args
  */
  public function onRun(mixed $player, string $aliasUsed, array $args) : void {
    try {
      if (!$player instanceof Player) {
        $this->sendConsoleError();
        return;
      }

      $player->sendMessage($this->getOwningPlugin()->messages->get('warp_list', 'unknow_message'));
      foreach (WarpManager::list() as $warp) {
        $player->sendMessage($warp->getName());
      }
    } catch (\Throwable $e) {
      new \crashdump($e);
    }
  }

}