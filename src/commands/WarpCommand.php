<?php

namespace commands;

use Warps;
use pocketmine\player\Player;
use libraries\commando\BaseCommand;

/**
* Class WarpCommand
*
* Represents the WarpCommand class.
*/
final class WarpCommand extends BaseCommand {

  private const BASE_PERMS = 'easywarps.admin';

  /**
  * Gets the permission required to execute this command.
  * @return string
  */
  public function getPermission() {
    return self::BASE_PERMS;
  }

  /**
  * Prepares the command for execution.
  */
  protected function prepare(): void {
    $this->setPermission(self::BASE_PERMS);
    $this->registerSubCommand(new arguments\Create(Warps::getInstance(), 'create', '§7Crie um novo ponto de teleport warp!'));
    $this->registerSubCommand(new arguments\Delete(Warps::getInstance(), 'delete', '§7Delete um ponto de teleport warp!'));
    $this->registerSubCommand(new arguments\Lista(Warps::getInstance(), 'list', '§7Veja os pontos de teleport warp salvos!'));
  }

  /**
  * Executes the command.
  *
  * @param mixed $player
  * @param string $aliasUsed
  * @param array $args
  */
  public function onRun(mixed $player, string $aliasUsed, array $args): void {
    try {
      if (!$player instanceof Player) {
        $this->sendConsoleError();
        return;
      }
      $player->sendMessage('Use /warp [args...]');
    } catch (\Throwable $e) {
      new \crashdump($e);
    }
  }
}