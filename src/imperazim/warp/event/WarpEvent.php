<?php

namespace imperazim\warp\event;

use imperazim\warp\Warp;
use pocketmine\event\Event;

/**
* Class WarpEvent
* @package imperazim\warp\event
*/
class WarpEvent extends Event {

  /** @var Warp */
  protected Warp $warp;

  /**
  * Get the associated warp.
  * @return Warp
  */
  public function getWarp() : Warp {
    return $this->warp;
  }
}