<?php

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

use libraries\utils\File;
use commands\WarpCommand;
use commands\WarpsCommand;

/**
* Class Warps
*/
final class Warps extends PluginBase {
  use SingletonTrait;

  /** @var File */
  private File $messages;

  /**
  * Called when the plugin is loaded.
  */
  protected function onLoad(): void {
    Warps::setInstance($this);
  }

  /**
  * Called when the plugin is enabled.
  */
  protected function onEnable() : void {
    if ($this->getServer()->getPluginManager()->getPlugin('EasyLibrary') === null) {
      $this->getLogger()->error('EasyLibrary is required!');
      $this->getLogger()->error('(https://github.com/ImperaZim/EasyLibrary/');
      $this->getServer()->forceShutdown();
      return;
    }
    $this->messages = new File($this, 'messages', '.yml');
    $this->getServer()->getPluginManager()->registerEvents(
      listener: new WarpsListener(),
      plugin: $this
    );
    $this->getServer()->getCommandMap()->registerAll(
      fallbackPrefix: 'SkyBlock',
      commands: [
        new WarpCommand(Warps::getInstance(), 'warp', '§7Sistema de warps'),
        new WarpsCommand(Warps::getInstance(), 'warps', '§7Sistema de warps'),
      ]
    );
  }

  /**
  * Get the message using id
  * @return string|null
  */
  public function getMessage(string $nested, mixed $default) : ?string {
    return $this->messages->get($nested, $default);
  }
}