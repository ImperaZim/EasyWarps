<?php

declare(strict_types = 1);

namespace imperazim\warp\form;

use pocketmine\player\Player;

use imperazim\warp\Warp;
use imperazim\warp\WarpManager;
use imperazim\warp\event\WarpCreateEvent;

use imperazim\components\ui\Form;
use imperazim\vendor\libform\Form as IForm;
use imperazim\vendor\libform\elements\Title;
use imperazim\vendor\libform\elements\Input;
use imperazim\vendor\libform\elements\Element;
use imperazim\vendor\libform\types\CustomForm;
use imperazim\vendor\libform\elements\StepSlider;
use imperazim\components\serialization\LocationSerializable;
use imperazim\vendor\libform\interaction\CustomElementsResponse;

/**
* Class WarpCreateForm
* @package imperazim\warp\form
*/
class WarpCreateForm extends Form {

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
    $file = WarpManager::getFile('settings');
    return new Title(
      text: $file->get('forms.warp_create_title', '')
    );
  }

  /**
  * Retrieves an array of elements for each available class.
  * @return Element[]
  */
  private function getElements(): array {
    $file = WarpManager::getFile('settings');
    return [
      new Input(
        text: $file->get('forms.warp_create_input_name.text', ''),
        placeholder: $file->get('forms.warp_create_input_name.placeholder', ''),
        default: '',
          identifier: 'name'
        ),
        new Input(
          text: $file->get('forms.warp_create_input_permission.text', ''),
          placeholder: $file->get('forms.warp_create_input_permission.placeholder', ''),
        default: '',
          identifier: 'permission'
        ),
        new Input(
          text: $file->get('forms.warp_create_input_button.text', ''),
          placeholder: $file->get('forms.warp_create_input_button.placeholder', ''),
        default: '',
          identifier: 'button'
        ),
        new Input(
          text: $file->get('forms.warp_create_input_image.text', ''),
          placeholder: $file->get('forms.warp_create_input_image.placeholder', ''),
        default: '',
          identifier: 'image'
        ),
        new Input(
          text: $file->get('forms.warp_create_input_message.text', ''),
          placeholder: $file->get('forms.warp_create_input_message.placeholder', ''),
        default: '',
          identifier: 'message'
        ),
        new StepSlider(
          text: $file->get('forms.warp_create_options_message.text', ''),
          options: ['chat', 'screen'],
        default: 0,
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
      $file = WarpManager::getFile('settings');
      $token = WarpManager::generateUniqueToken();

      $name = $response->getElement('name')->getValue();
      $permission = $response->getElement('permission')->getValue();
      $button = $response->getElement('button')->getValue();
      $image = $response->getElement('image')->getValue();
      $message = $response->getElement('message')->getValue();
      $type = $response->getElement('type')->getSelectedOption();

      if (empty($name)) {
        $name = $token;
      }

      if (strlen($permission) < 1) {
        $permission = null;
      }

      if (empty($image)) {
        $image = 'null';
      }

      if (empty($message)) {
        $message = '';
      }

      if (empty($button) || strtolower($button) === 'default') {
        $button = '§l{warp}{line}teleport';
      }

      $warps = WarpManager::getFile('warps');
      $list = $warps->get('list', []);
      $list[$token] = [
        'name' => $name,
        'permission' => $permission,
        'button' => $button,
        'image' => $image,
        'location' => LocationSerializable::jsonSerialize($player->getLocation()),
        'message' => [
          'text' => $message,
          'type' => $type,
        ]
      ];

      try {
        $warps->set(['list' => $list]);

        $player->sendMessage(
          WarpManager::getText(
            messageId: 'settings:messages.warp_created_successfully',
            tags: [
              'warp' => ($name === null ? $token : $name)
            ],
            defaultValue: ''
          )
        );
        new WarpCreateEvent(new Warp($token), $player);
      } catch (\Exception $e) {
        new \crashdump($e);
      }
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