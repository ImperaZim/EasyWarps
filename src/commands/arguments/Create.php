<?php

namespace commands\arguments;

use warp\Warp;
use warp\WarpManager;
use events\WarpCreateEvent;
use pocketmine\player\Player;
use libraries\commando\BaseSubCommand;
use libraries\commando\args\RawStringArgument;

/**
* Class Create
* @package commands\island\arguments
*/
class Create extends BaseSubCommand {

  /**
  * Set up the command, including permissions and arguments.
  */
  protected function prepare() : void {
    $this->setPermission('easywarps.admin');
    $this->registerArgument(0, new RawStringArgument('name'));
    $this->registerArgument(1, new RawStringArgument('permission', true));
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
      $permission = $args['permission'] ?? 'easywarps.default';
      if (WarpManager::create($name, $permission, $player->getPosition())) {
        $ev = new WarpCreateEvent(new Warp($name), $player);
        $ev->call();
      } else {
        $player->sendMessage(str_replace('{WARP}', $name, $this->getOwningPlugin()->messages->get('warp_created_fail', 'unknow_message')));
      }
    } catch (\Throwable $e) {
      new \crashdump($e);
    }
  }

}