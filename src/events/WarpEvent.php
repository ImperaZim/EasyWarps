<?php

namespace events;

use warp\Warp;
use pocketmine\event\Event;

/**
* Class WarpEvent
* @package event
*/
class WarpEvent extends Event {

  /**
  * @param Warp|null $Warp
  */
  public function __construct(private ?Warp $warp) {}

  /**
  * Get the associated warp.
  * @return Warp|null
  */
  public function getWarp() : ?Warp {
    return $this->warp;
  }
}