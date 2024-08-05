<?php

declare(strict_types = 1);

namespace imperazim\warp\command\subcommand;

use pocketmine\player\Player;
use imperazim\warp\WarpManager;
use imperazim\warp\form\WarpCreateForm;
use imperazim\vendor\commando\BaseSubCommand;
use imperazim\vendor\commando\constraint\InGameRequiredConstraint;

/**
* Class WarpCreateSubcommand
* @package imperazim\warp\command\subcommand
*/
final class WarpCreateSubcommand extends BaseSubCommand {

  /**
  * WarpCreateSubcommand constructor.
  */
  public function __construct() {
    $file = WarpManager::getFile('settings');
    parent::__construct(
      plugin: WarpManager::getPlugin(),
      names: $file->get('subcommands.create_names', ['create']),
      description: $file->get('subcommands.create_description', ''),
    );
  }

  /**
  * Prepares the subcommand for execution.
  */
  protected function prepare(): void {
    $this->setPermission('easywarps.admin');
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
    new WarpCreateForm($player, []);
  }
}