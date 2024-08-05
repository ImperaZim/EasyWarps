<?php

namespace imperazim\warp\event;

use imperazim\warp\Warp;
use pocketmine\player\Player;

/**
* Class WarpCreateEvent
* @package imperazim\warp\event
*/
final class WarpCreateEvent extends WarpEvent {
  
  /** @var Player */
  protected Player $author;

  /**
  * @param Warp $warp
  * @param Player $author
  */
  public function __construct(Warp $warp, Player $author) {
    $this->warp = $warp;
    $this->author = $author;
  }

  /**
  * Get the associated author.
  * @return Player
  */
  public function getAuthor(): Player {
    return $this->author;
  }
}