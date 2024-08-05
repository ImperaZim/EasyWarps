<?php

declare(strict_types = 1);

namespace imperazim\warp\command;

use pocketmine\player\Player;
use imperazim\warp\WarpManager;
use imperazim\warp\form\WarpsForm;
use imperazim\vendor\commando\BaseSubCommand;
use imperazim\vendor\commando\constraint\InGameRequiredConstraint;

/**
* Class WarpsCommand
* @package imperazim\warp\command
*/
final class WarpsCommand extends BaseSubCommand {

  /**
  * WarpsCommand constructor.
  */
  public function __construct() {
    $file = WarpManager::getFile('settings');
    parent::__construct(
      plugin: WarpManager::getPlugin(),
      names: $file->get('commands.warps_names', ['warps']),
      description: $file->get('commands.warps_description', ''),
    );
  }

  /**
  * Prepares the subcommand for execution.
  */
  protected function prepare(): void {
    $this->setPermission('easywarps.default');
    $this->addConstraints([
      new InGameRequiredConstraint($this)
    ]);
  }

  /**
  * Executes the subcommand.
  * @param mixed $player
  * @param string $aliasUsed
  * @param array $args
  */
  public function onRun(mixed $player, string $aliasUsed, array $args): void {
    new WarpsForm($player, []);
  }
}