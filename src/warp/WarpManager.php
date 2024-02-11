<?php

namespace warp;

use libraries\utils\File;
use pocketmine\world\Position;

/**
* Class WarpManager
* Manages warps.
* @package warp
*/
final class WarpManager {

  /**
  * Get the configuration file for warps.
  * @return File
  */
  public static function getConfig() : File {
    return new File('warps');
  }

  /**
  * Create a new warp.
  * @param string $name
  * @param string $permission
  * @param Position $position
  * @return bool
  */
  public static function create(string $name, string $permission, Position $position) : bool {
    $warps = self::getConfig()->get('warps', []);
    if (!isset($warps[$name])) {
      $data = [
        'permission' => $permission,
        'position' => [
          'x' => $position->getX(),
          'y' => $position->getY(),
          'z' => $position->getZ(),
          'world' => $position->getWorld()->getName()
        ]
      ];
      self::getConfig()->set(['warps.' . $name => $data]);
      return true;
    }
    return false;
  }

  /**
  * Delete a warp.
  * @param string $name
  * @return bool
  */
  public static function delete(string $name) : bool {
    $warps = self::getConfig()->get('warps', []);
    if (isset($warps[$name])) {
      unset($warps[$name]);
      self::getConfig()->set(['warps' => $warps]);
      return true;
    }
    return false;
  }

  /**
  * Get a list of all warps.
  * @return Warp[]
  */
  public static function list() : array {
    $list = [];
    $warps = self::getConfig()->get('warps', []);
    foreach ($warps as $name => $data) {
      $list[] = new Warp($name);
    }
    return $list;
  }

}