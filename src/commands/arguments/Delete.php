<?php

namespace commands\arguments;

use warp\Warp;
use warp\WarpManager;
use events\WarpDeleteEvent;
use pocketmine\player\Player;
use libraries\commando\BaseSubCommand;
use libraries\commando\args\RawStringArgument;

/**
* Class Delete
* @package commands\island\arguments
*/
class Delete extends BaseSubCommand {

  /**
  * Set up the command, including permissions and arguments.
  */
  protected function prepare() : void {
    $this->setPermission('easywarps.admin');
    $this->registerArgument(0, new RawStringArgument('name'));
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

      $name = $args['name'];
      if (WarpManager::delete($name)) {
        $ev = new WarpDeleteEvent(new Warp($name), $player);
        $ev->call();
      } else {
        $player->sendMessage(str_replace('{WARP}', $name, $this->getOwningPlugin()->getMessage('warp_deleted_fail', 'unknow_message')));
      }
    } catch (\Throwable $e) {
      new \crashdump($e);
    }
  }

}