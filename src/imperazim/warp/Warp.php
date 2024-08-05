<?php

declare(strict_types = 1);

namespace imperazim\warp;

use pocketmine\player\Player;
use pocketmine\entity\Location;

use imperazim\warp\WarpManager;
use imperazim\warp\event\WarpTeleportEvent;

use imperazim\components\filesystem\File;
use imperazim\vendor\libform\elements\Image;
use imperazim\vendor\libform\elements\Button;
use imperazim\vendor\libform\interaction\ButtonResponse;
use imperazim\components\serialization\LocationSerializable;

/**
* Class Warp
* @package imperazim\warp
*/
final class Warp {

  /** @var array */
  private array $warps;

  /**
  * Warp constructor.
  * @param string $token
  */
  public function __construct(private string $token) {
    $this->warps = WarpManager::getFile('warps')->get('list', []);
  }

  /**
  * Get the token of the warp.
  * @return string The token of the warp.
  */
  public function getToken(): string {
    return $this->token;
  }

  /**
  * Check if the warp exists.
  * @return bool
  */
  public function exists(): bool {
    return ($this->getWarpData() !== null);
  }

  /**
  * Gets the array of WarpData.
  * @return array|null
  */
  public function getWarpData(): ?array {
    return $this->warps[$this->token] ?? null;
  }

  /**
  * Sets values in WarpData.
  */
  public function setWarpData(string $key, mixed $value): void {
    if ($this->exists()) {
      $this->warps[$this->token][$key] = $value;
      WarpManager::getFile('warps')->set(['list' => $this->warps]);
    }
  }

  /**
  * Sets the name of the warp in the warp data.
  * @param string $name
  */
  public function setName(string $name): void {
    $this->setWarpData('name', $name);
  }

  /**
  * Gets the name of the warp from the warp data.
  * @return string|null
  */
  public function getName(): ?string {
    return $this->getWarpData()['name'] ?? null;
  }

  /**
  * Sets the permission of the warp in the warp data.
  * @param string|null $permission
  */
  public function setPermission(?string $permission): void {
    $this->setWarpData('permission', $permission);
  }

  /**
  * Gets the permission of the warp from the warp data.
  * @return string|null
  */
  public function getPermission(): ?string {
    return $this->getWarpData()['permission'] ?? null;
  }

  /**
  * Gets the location of the warp.
  * @return ?Location
  */
  public function getLocation(): ?Location {
    $location = $this->getWarpData()['location'] ?? null;
    if ($location === null) {
      return null;
    }
    return LocationSerializable::jsonDeserialize($location);
  }

  /**
  * Sets the location of the warp.
  * @param Location $location
  */
  public function setLocation(Location $location): void {
    $this->setWarpData('location', LocationSerializable::jsonSerialize($location));
  }

  /**
  * Gets the button of the warp.
  * @return string|null
  */
  public function getButtonText(): ?string {
    return $this->getWarpData()['button'] ?? '§l{warp}\nteleport';
  }

  /**
  * Sets the button of the warp.
  * @param string $button
  */
  public function setButtonText(string $button): void {
    $this->setWarpData('button', $button);
  }

  /**
  * Gets the image of the warp.
  * @return Image|null
  */
  public function getImage(): ?Image {
    return Image::fromString(
      $this->getWarpData()['image'] ?? 'null'
    );
  }

  /**
  * Sets the image of the warp.
  * @param Image $image
  */
  public function setImage(Image $image): void {
    $image = $image->jsonSerialize();
    $imageValues = array_values($image);
    $this->setWarpData('image', implode('|', $imageValues));
  }

  /**
  * Gets the message of the warp.
  * @return array|null
  */
  public function getMessage(): ?array {
    return $this->getWarpData()['message'] ?? null;
  }

  /**
  * Sets the message of the warp.
  * @param string $text
  * @param string $type
  */
  public function setMessage(string $text, string $type): void {
    $message = [
      'text' => $text,
      'type' => $type
    ];
    $this->setWarpData('message', $message);
  }

  /**
  * Gets the button form of the warp.
  * @return Button|null
  */
  public function getButton(): ?Button {
    $text = [];
    $template = $this->getButtonText();
    $file = WarpManager::getFile('settings');
    $text = str_replace('{warp}', $this->getName(), $template);

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

    return new Button(
      text: $text,
      image: $this->getImage(),
      value: $this->getToken(),
      onclick: new ButtonResponse(
        function (Player $player, Button $button): void {
          if ($this->getPermission() !== null && (!$player->hasPermission($this->getPermission()))) {
            $player->sendMessage(
              WarpManager::getText(
                messageId: 'settings:messages.no_permission',
                tags: [
                  'warp' => $this->getName()
                ],
                defaultValue: ''
              )
            );
            return;
          }
          if ($this->getLocation() === null) {
            $player->sendMessage(
              WarpManager::getText(
                messageId: 'settings:messages.world_error',
                tags: [
                  'warp' => $this->getName()
                ],
                defaultValue: ''
              )
            );
            return;
          }
          $player->teleport($this->getLocation());
          new WarpTeleportEvent($this, $player);

          $message = $this->getMessage();
          if ($message !== null && isset($message['text'])) {
            if ($message['type'] === 'chat') {
              $player->sendMessage(str_replace('{warp}', $this->getName(), $message['text']));
            }
            if ($message['type'] === 'screen') {
              $messageSplited = explode("|", $message['text']);
              $messageSplited = array_map(fn($message) => str_replace('{warp}', $this->getName(), $message), $messageSplited);
              if (isset($messageSplited[1])) {
                $player->sendTitle($messageSplited[0], $messageSplited[1]);
              } else {
                $player->sendTitle($messageSplited[0]);
              }
            }
          }
        }
      ),
      reopen: false
    );
  }
}