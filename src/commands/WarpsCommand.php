<?php

namespace commands;

use warp\Warp;
use warp\WarpManager;
use pocketmine\Player;
use libraries\form\MenuForm;
use libraries\form\FormMaker;
use events\WarpTeleportEvent;
use libraries\form\menu\Button;
use libraries\commando\BaseCommand;

/**
* Class WarpCommand
*
* Represents the WarpCommand class.
*/
final class WarpsCommand extends BaseCommand {

  private const BASE_PERMS = 'easywarps.admin';

  /**
  * Gets the permission required to execute this command.
  *
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
    $this->registerSubCommand(new arguments\Create('create', '§7Crie um novo ponto de teleport warp!'));
    $this->registerSubCommand(new arguments\Delete('delete', '§7Delete um ponto de teleport warp!'));
    $this->registerSubCommand(new arguments\Lista('list', '§7Veja os pontos de teleport warp salvos!'));
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
      if (!$player instanceof Player) {
        $player->sendMessage('Use /warp [args...]');
      }
      new WarpsForms($player, []);
    } catch (\Throwable $e) {
      new \crashdump($e);
    }
  }
}

final class WarpsForms extends FormMaker {

  /**
  * Generates and sends the form to the player.
  */
  public function makeForm() : void {
    try {
      $this->setBaseForm(
        form: new MenuForm(
          title: \Plugin::getInstance()->messages->get('warp_created_fail', 'unknow_message'),
          buttons: (array) $this->getButtons(),
          onSubmit: fn($player, $button) => $this->getSubmitCallback(
            player: $player,
            button: $button
          )
        )
      )->send();
    } catch (\Throwable $e) {
      new \crashdump($e);
    }
  }

  /**
  * Retrieves an array of buttons for each available class.
  * @return Button[]
  */
  private function getButtons(): array {
    $buttons = [];
    foreach (WarpManager::list() as $warp) {
      $buttons[] = new Button(
        text: [explode(':', str_replace(['{WARP}', '{LINE_UP}'], [$warp->getName(), ':'], \Plugin::getInstance()->messages->get('warp_list_button', 'unknow_message')))],
        image: Image::null(),
        value: $warp->getName()
      );
    }

    return $buttons;
  }

  /**
  * Handles the form submission and returns the next form to display.
  * @param Player $player
  * @param Button $button
  * @return FormMaker|null
  */
  private function getSubmitCallback(Player $player, Button $button): ?FormMaker {
    try {
      $warp = new Warp($button->getValue());
      if (!$warp->exists()) {
        $player->sendMessage(str_replace('{WARP}', $warp->getName(), \Plugin::getInstance()->messages->get('warp_dont_exist', 'unknow_message')));
        return null;
      }
      if (!$player->hasPermission($warp->getPermission())) {
        $player->sendMessage(str_replace('{WARP}', $warp->getName(), \Plugin::getInstance()->messages->get('warp_teleported_no_permission', 'unknow_message')));
        return null;
      }
      
      $position = $warp->getPosition();
      $manager = \Plugin::getInstance()->getServer()->getWorldManager();
      $manager->isWorldLoaded($position->getWorld()->getName()) && $manager->loadWorld($position->getWorld()->getName(), true);
      
      if ($player->teleport($position)) {
        $ev = new WarpTeleportEvent(new Warp($name), $player);
        $ev->call();
      }
    } catch (\Throwable $e) {
      new \crashdump($e);
    }
    return null;
  }

}