<?php

use events\WarpCreateEvent;
use events\WarpDeleteEvent;
use events\WarpTeleportEvent;
use pocketmine\event\Listener;

/**
* Class PluginListener
* @package YourPluginNamespace
*/
final class PluginListener implements Listener {

  /**
  * Handles the event when a warp is created.
  * @param WarpCreateEvent $event
  */
  public function onWarpCreation(WarpCreateEvent $event) : void {
    $warp = $event->getWarp();
    $player = $event->getPlayer();
    $player->sendMessage(str_replace('{WARP}', $warp->getName(), Plugin::getInstance()->messages->get('warp_created_successfully', 'unknown_message')));
  }

  /**
  * Handles the event when a warp is deleted.
  * @param WarpDeleteEvent $event
  */
  public function onWarpDeletion(WarpDeleteEvent $event) : void {
    $warp = $event->getWarp();
    $player = $event->getPlayer();
    $player->sendMessage(str_replace('{WARP}', $warp->getName(), Plugin::getInstance()->messages->get('warp_deleted_successfully', 'unknown_message')));
  }

  /**
  * Handles the event when a player teleports using a warp.
  * @param WarpTeleportEvent $event
  */
  public function onWarpTeleport(WarpTeleportEvent $event) : void {
    $warp = $event->getWarp();
    $player = $event->getPlayer();
    $player->sendMessage(str_replace('{WARP}', $warp->getName(), Plugin::getInstance()->messages->get('warp_teleported_successfully', 'unknown_message')));
  }
}