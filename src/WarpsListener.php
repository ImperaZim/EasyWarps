<?php

use events\WarpCreateEvent;
use events\WarpDeleteEvent;
use events\WarpTeleportEvent;
use pocketmine\event\Listener;

/**
* Class WarpsListener
*/
final class WarpsListener implements Listener {

  /**
  * Handles the event when a warp is created.
  * @param WarpCreateEvent $event
  */
  public function onWarpCreation(WarpCreateEvent $event) : void {
    $warp = $event->getWarp();
    $player = $event->getPlayer();
    $player->sendMessage(str_replace('{WARP}', $warp->getName(), Warps::getInstance()->getMessage('warp_created_successfully', 'unknown_message')));
  }

  /**
  * Handles the event when a warp is deleted.
  * @param WarpDeleteEvent $event
  */
  public function onWarpDeletion(WarpDeleteEvent $event) : void {
    $warp = $event->getWarp();
    $player = $event->getPlayer();
    $player->sendMessage(str_replace('{WARP}', $warp->getName(), Warps::getInstance()->getMessage('warp_deleted_successfully', 'unknown_message')));
  }

  /**
  * Handles the event when a player teleports using a warp.
  * @param WarpTeleportEvent $event
  */
  public function onWarpTeleport(WarpTeleportEvent $event) : void {
    $warp = $event->getWarp();
    $player = $event->getPlayer();
    $player->sendMessage(str_replace('{WARP}', $warp->getName(), Warps::getInstance()->getMessage('warp_teleported_successfully', 'unknown_message')));
  }
}