<?php

declare(strict_types = 1);

namespace imperazim\warp\form;

use pocketmine\player\Player;

use imperazim\warp\WarpManager;
use imperazim\components\ui\Form;
use imperazim\vendor\libform\Form as IForm;
use imperazim\vendor\libform\elements\Image;
use imperazim\vendor\libform\elements\Title;
use imperazim\vendor\libform\elements\Input;
use imperazim\vendor\libform\elements\Element;
use imperazim\vendor\libform\types\CustomForm;
use imperazim\vendor\libform\elements\StepSlider;
use imperazim\vendor\libform\interaction\CustomElementsResponse;

/**
* Class WarpEditForm
* @package imperazim\warp\form
*/
class WarpEditForm extends Form {

  /**
  * Defines the form structure.
  * @return IForm
  */
  public function structure(): IForm {
    return new CustomForm(
      title: $this->getTitle(),
      elements: $this->getElements(),
      onSubmit: fn($player, $response) => $this->handleSubmit($player, $response),
      onClose: fn($player) => $this->handleClose($player)
    );
  }

  /**
  * Gets the title.
  * @return Title
  */
  private function getTitle(): Title {
    $warp = $this->getProcessedData('warp');
    $file = WarpManager::getFile('settings');
    return new Title(
      text: str_replace(
        '{warp}',
        $warp->getToken(),
        $file->get('forms.warp_edit_title', '')
      )
    );
  }

  /**
  * Retrieves an array of elements for each available class.
  * @return Element[]
  */
  private function getElements(): array {
    $warp = $this->getProcessedData('warp');
    $file = WarpManager::getFile('settings');
    return [
      new Input(
        text: $file->get('forms.warp_edit_input_name.text', ''),
        placeholder: $file->get('forms.warp_edit_input_name.placeholder', ''),
        default: $warp->getName(),
          identifier: 'name'
        ),
        new Input(
          text: $file->get('forms.warp_edit_input_permission.text', ''),
          placeholder: $file->get('forms.warp_edit_input_permission.placeholder', ''),
        default: $warp->getPermission() == null ? '' : $warp->getPermission(),
          identifier: 'permission'
        ),
        new Input(
          text: $file->get('forms.warp_edit_input_button.text', ''),
          placeholder: $file->get('forms.warp_edit_input_button.placeholder', ''),
        default: $warp->getButtonText(),
          identifier: 'button'
        ),
        new Input(
          text: $file->get('forms.warp_edit_input_image.text', ''),
          placeholder: $file->get('forms.warp_edit_input_image.placeholder', ''),
        default: $warp->getImage() === null ? '' : implode('|', array_values($warp->getImage()->jsonSerialize())),
          identifier: 'image'
        ),
        new Input(
          text: $file->get('forms.warp_edit_input_message.text', ''),
          placeholder: $file->get('forms.warp_edit_input_message.placeholder', ''),
        default: $warp->getMessage()['text'],
          identifier: 'message'
        ),
        new StepSlider(
          text: $file->get('forms.warp_edit_options_message.text', ''),
          options: ['chat', 'screen'],
        default: $warp->getMessage()['type'] == 'chat' ? 0 : 1,
          identifier: 'type'
        )
      ];
    }

    /**
    * Handles the form submission and returns the next form to display.
    * @param Player $player
    * @param CustomElementsResponse $response
    */
    private function handleSubmit(Player $player, CustomElementsResponse $response): void {
      $warp = $this->getProcessedData('warp');
      $file = WarpManager::getFile('settings');

      $name = $response->getElement('name')->getValue();
      $permission = $response->getElement('permission')->getValue();
      $button = $response->getElement('button')->getValue();
      $image = $response->getElement('image')->getValue();
      $message = $response->getElement('message')->getValue();
      $type = $response->getElement('type')->getSelectedOption();

      if (!empty($name)) {
        $warp->setName($name);
      }

      if (strlen($permission) < 1) {
        $warp->setPermission(strlen($permission) >= 1 ? $permission : null,);
      }

      if (!empty($image)) {
        $warp->setImage(Image::fromString($image));
      }

      if (!empty($message)) {
        $warp->setMessage($message, $type);
      }

      if (!empty($button)) {
        $warp->setButtonText($button);
      } elseif (strtolower($button) === 'default') {
        $warp->setButtonText('§l{warp}{up}teleport');
      } else {
        $warp->setButtonText('§l{warp}{up}teleport');
      }

      $player->sendMessage(
        WarpManager::getText(
          messageId: 'settings:messages.warp_updated_successfully',
          tags: [
            'warp' => $warp->getToken()
          ],
          defaultValue: ''
        )
      );

    }

    /**
    * Handles the form closure and returns the next form to display.
    * @param Player $player
    */
    private function handleClose(Player $player): void {
      /**
      * TODO: NULL
      */
    }
  }