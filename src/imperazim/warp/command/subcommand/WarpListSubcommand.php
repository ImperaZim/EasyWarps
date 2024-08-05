<?php

declare(strict_types = 1);

namespace imperazim\warp\command\subcommand;

use pocketmine\player\Player;
use imperazim\warp\WarpManager;
use imperazim\vendor\commando\BaseSubCommand;

/**
* Class WarpListSubcommand
* @package imperazim\warp\command\subcommand
*/
final class WarpListSubcommand extends BaseSubCommand {

  /**
  * WarpListSubcommand constructor.
  */
  public function __construct() {
    $file = WarpManager::getFile('settings');
    parent::__construct(
      plugin: WarpManager::getPlugin(),
      names: $file->get('subcommands.list_names', ['list']),
      description: $file->get('subcommands.list_description', ''),
    );
  }

  /**
  * Prepares the subcommand for execution.
  */
  protected function prepare(): void {
    $this->setPermission('easywarps.admin');
  }

  /**
  * Executes the subcommand.
  * @param mixed $player
  * @param string $aliasUsed
  * @param array $args
  */
  public function onRun(mixed $player, string $aliasUsed, array $args): void {
    $list = [];
    $file = WarpManager::getFile('settings');

    foreach (WarpManager::getWarps() as $warp) {
      $list[] = $warp->getToken() . ': ' . ($warp->getName() ?? '');
    }

    $player->sendMessage(
      WarpManager::getText(
        messageId: 'settings:messages.warp_list',
        tags: [
          'list' => empty($list) ? $file->get('forms.warp_list_empty_content', '') : implode("\n", $list)
        ],
        defaultValue: ''
      )
    );
  }
}