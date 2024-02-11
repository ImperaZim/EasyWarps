<?php

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

use libraries\utils\File;
use commands\WarpCommand;
use commands\WarpsCommand;
use libraries\commando\PacketHooker;

/**
 * Class Plugin
 * @package YourPluginNamespace
 */
final class Plugin extends PluginBase {
  use SingletonTrait;
  
  /** @var File */
  public File $messages;

  /** @var PacketHooker */
  public PacketHooker $hooker;

  /**
   * Called when the plugin is loaded.
   */
  public function onLoad() : void {
    self::setInstance($this);
  }

  /**
   * Called when the plugin is enabled.
   */
  public function onEnable() : void {
    $this->hooker = new PacketHooker($this);
    $this->messages = new File('messages', 'YML');
    
    $this->getServer()->getPluginManager()->registerEvents(
      listener: new PluginListener(),
      plugin: $this
    );
    
    $this->getServer()->getCommandMap()->registerAll(
      fallbackPrefix: 'SkyBlock',
      commands: [
        new WarpCommand('warp', '§7Sistema de warps'),
        new WarpsCommand('warps', '§7Sistema de warps'),
      ]
    );
  }
}
