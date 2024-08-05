<?php

declare(strict_types = 1);

namespace imperazim;

use imperazim\components\plugin\PluginToolkit;
use imperazim\components\plugin\traits\PluginToolkitTrait;

use imperazim\warp\WarpManager;

/**
* Class EasyWarps
* @package imperazim
*/
final class EasyWarps extends PluginToolkit {
  use PluginToolkitTrait;

  /**
  * This method is called when the plugin is enabled.
  */
  protected function onEnable(): void {
    $this->saveRecursiveResources();
    $this->addComponent($this, WarpManager::class);
  }
}