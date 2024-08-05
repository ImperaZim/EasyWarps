<?php

declare(strict_types = 1);

namespace imperazim\warp\form;

use pocketmine\player\Player;

use imperazim\warp\WarpManager;
use imperazim\components\ui\Form;
use imperazim\vendor\libform\Form as IForm;
use imperazim\vendor\libform\types\LongForm;
use imperazim\vendor\libform\elements\Title;
use imperazim\vendor\libform\elements\Content;

/**
* Class WarpsForm
* @package imperazim\warp\form
*/
final class WarpsForm extends Form {

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
      text: $file->get('forms.warp_list_title', '')
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
      $buttons[] = $warp->getButton();
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