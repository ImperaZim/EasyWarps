<?php

namespace imperazim\warp\event;

use imperazim\warp\Warp;
use pocketmine\player\Player;

/**
* Class WarpTeleportEvent
* @package imperazim\warp\event
*/
final class WarpTeleportEvent extends WarpEvent {
  
  /** @var Player */
  protected Player $player;

  /**
  * @param Warp $warp
  * @param Player $player
  */
  public function __construct(Warp $warp, Player $player) {
    $this->warp = $warp;
    $this->player = $player;
  }

  /**
  * Get the associated player.
  * @return Player
  */
  public function getPlayer(): Player {
    return $this->player;
  }
}