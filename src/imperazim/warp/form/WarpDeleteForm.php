<?php

declare(strict_types = 1);

namespace imperazim\warp\form;

use pocketmine\player\Player;

use imperazim\warp\Warp;
use imperazim\warp\WarpManager;
use imperazim\warp\event\WarpDeleteEvent;

use imperazim\components\ui\Form;
use imperazim\vendor\libform\Form as IForm;
use imperazim\vendor\libform\types\LongForm;
use imperazim\vendor\libform\elements\Title;
use imperazim\vendor\libform\elements\Button;
use imperazim\vendor\libform\elements\Content;
use imperazim\vendor\libform\interaction\ButtonResponse;

/**
* Class WarpDeleteForm
* @package imperazim\warp\form
*/
final class WarpDeleteForm extends Form {

  /**
  * Defines the form structure.
  */
  public function structure(): IForm {
    return new LongForm(
      title: $this->getTitle(),
      content: $this->getContent(),
      buttons: $this->getButtons(),
      onClose: fn($player) => $this->getCloseCallback($player)
    );
  }

  /**
  * Gets the title.
  * @return Title
  */
  private function getTitle(): Title {
    $file = WarpManager::getFile('settings');
    return new Title(
      text: $file->get('forms.warp_delete_title', '')
    );
  }

  /**
  * Gets the content.
  * @return Content
  */
  private function getContent(): Content {
    $file = WarpManager::getFile('settings');
    return new Content(
      text: (count(WarpManager::getWarps()) >= 1 ? '' : $file->get('forms.warp_list_empty_content', ''))
    );
  }

  /**
  * Retrieves an array of buttons for each available class.
  * @return Button[]
  */
  private function getButtons(): array {
    $buttons = [];
    $warps = WarpManager::getWarps();
    foreach ($warps as $warp) {
      $text = WarpManager::getText(
        messageId: 'settings:forms.warp_delete_button',
        tags: [
          'warp' => $warp->getName()
        ],
        defaultValue: ''
      );

      if (strpos($text, '{line}') !== false) {
        $text = explode('{line}', $text);
      } elseif (strpos($text, '{up}') !== false) {
        $text = explode('{up}', $text);
      } else {
        $text = [$text];
      }

      if (count($text) > 2) {
        $text = array_slice($text, 0, 2);
      }

      if (count($text) == 1) {
        $text[] = '';
      }

      $buttons[] = new Button(
        text: $text,
        image: $warp->getImage(),
        value: $warp->getToken(),
        onclick: new ButtonResponse(
          function (Player $player, Button $button) use ($warp): void {
            $token = $warp->getToken();
            
            $file = WarpManager::getFile('warps');
            $warps = $file->get('list', []);
            if (!array_key_exists($token, $warps)) {
              return;
            }
            unset($warps[$token]);
            $file->set(['list' => $warps]);
            $player->sendMessage(
              WarpManager::getText(
                messageId: 'settings:messages.warp_delete_success',
                tags: [
                  'warp' => $token
                ],
                defaultValue: ''
              )
            );
            new WarpDeleteEvent($warp, $player);
          }
        ),
        reopen: true
      );
    }
    return $buttons;
  }

  /**
  * Handles the form closure and returns the next form to display.
  * @param Player $player
  * @return Form|null
  */
  private function getCloseCallback(Player $player): void {
    /**
    * TODO: current null
    */
  }
}