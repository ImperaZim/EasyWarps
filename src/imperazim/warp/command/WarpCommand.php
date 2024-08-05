<?php

declare(strict_types = 1);

namespace imperazim\warp\command;

use pocketmine\player\Player;

use imperazim\vendor\commando\BaseCommand;
use imperazim\vendor\commando\constraint\InGameRequiredConstraint;

use imperazim\warp\WarpManager;
use imperazim\warp\command\subcommand\WarpListSubcommand;
use imperazim\warp\command\subcommand\WarpEditSubcommand;
use imperazim\warp\command\subcommand\WarpCreateSubcommand;
use imperazim\warp\command\subcommand\WarpDeleteSubcommand;

/**
* Class WarpCommand
* @package imperazim\warp\command
*/
final class WarpCommand extends BaseCommand {

  /**
  * WarpCommand constructor.
  */
  public function __construct() {
    $file = WarpManager::getFile('settings');
    parent::__construct(
      plugin: WarpManager::getPlugin(),
      names: $file->get('commands.warp_names', ['warp']),
      description: $file->get('commands.warp_description', ''),
    );
  }

  /**
  * Prepares the command for execution.
  */
  protected function prepare(): void {
    $this->setPermission('easywarps.admin');
    $this->addConstraints([
      new InGameRequiredConstraint($this)
    ]);
    $this->registerSubcommands([
      new WarpListSubcommand(),
      new WarpEditSubcommand(),
      new WarpCreateSubcommand(),
      new WarpDeleteSubcommand(),
    ]);
  }

  /**
  * Executes the command.
  * @param mixed $player
  * @param string $aliasUsed
  * @param array $args
  */
  public function onRun(mixed $player, string $aliasUsed, array $args): void {
    foreach ($this->getSubCommands() as $subcommand) {
      $player->sendMessage($subcommand->getUsage());
    }
  }
}