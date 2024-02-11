<?php

namespace warp;

use pocketmine\Server;
use libraries\utils\File;
use pocketmine\world\Position;

/**
* Class Warp
* Represents a warp point.
* @package warp
*/
final class Warp {

  /**
  * @var File
  */
  private File $file;

  /**
  * Warp constructor.
  * @param string|null $name
  */
  public function __construct(private ?string $name) {
    $this->file = new File('warps');
  }

  /**
  * Check if the warp exists.
  * @return bool
  */
  public function exists() : bool {
    $find = false;
    foreach (WarpManager::list() as $warp) {
      if ($warp->getName() == $this->getName()) {
        $find = true;
        break;
      }
    }
    return $find;
  }

  /**
  * Get the name of the warp.
  * @return string|null
  */
  public function getName() : ?string {
    return $this->name;
  }

  /**
  * Get the icon associated with the warp.
  * @return string|null
  */
  public function getIcon() : ?string {
    return $this->file->get('warps.' . $this->name . '.icon');
  }

  /**
  * Get the permission required to access the warp.
  * @return string|null
  */
  public function getPermission() : ?string {
    return $this->file->get('warps.' . $this->name . '.permission');
  }

  /**
  * Get the position of the warp.
  * @return Position|null
  */
  public function getPosition() : ?Position {
    $position = $this->file->get('warps.' . $this->name . '.position');
    return new Position(
      x: $position['x'],
      y: $position['y'],
      z: $position['z'],
      world: Server::getInstance()->getWorldManager()->getWorldByName($position['world'])
    );
  }

}