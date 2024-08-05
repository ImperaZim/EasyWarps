<?php

declare(strict_types = 1);

namespace imperazim\warp\command\subcommand;

use pocketmine\player\Player;

use imperazim\warp\Warp;
use imperazim\warp\WarpManager;
use imperazim\warp\form\WarpSelectorEditForm;
use imperazim\vendor\commando\BaseSubCommand;
use imperazim\vendor\commando\constraint\InGameRequiredConstraint;

/**
* Class WarpEditSubcommand
* @package imperazim\warp\command\subcommand
*/
final class WarpEditSubcommand extends BaseSubCommand {

  /**
  * WarpEditSubcommand constructor.
  */
  public function __construct() {
    $file = WarpManager::getFile('settings');
    parent::__construct(
      plugin: WarpManager::getPlugin(),
      names: $file->get('subcommands.edit_names', ['edit']),
      description: $file->get('subcommands.edit_description', ''),
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
    new WarpSelectorEditForm($player, []);
  }
}