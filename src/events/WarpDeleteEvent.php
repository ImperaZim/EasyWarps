<?php

namespace events;

use warp\Warp;
use pocketmine\player\Player;

/**
* Class WarpDeleteEvent
* @package event
*/
final class WarpDeleteEvent extends WarpEvent {

  /**
  * @param Warp|null $Warp
  * @param Player|null $player
  */
  public function __construct(private ?Warp $warp, private ?Player $player) {
    parent::__construct($warp);
  }

  /**
  * Get the associated player.
  * @return Player|null
  */
  public function getPlayer() : ?Player {
    return $this->player;
  }
}