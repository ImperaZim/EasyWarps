<?php

declare(strict_types = 1);

namespace imperazim\warp;

use pocketmine\player\Player;
use pocketmine\entity\Location;

use imperazim\warp\command\WarpCommand;
use imperazim\warp\command\WarpsCommand;

use imperazim\components\utils\Text;
use imperazim\components\filesystem\File;
use imperazim\vendor\libform\elements\Image;
use imperazim\components\plugin\PluginToolkit;
use imperazim\components\plugin\PluginComponent;
use imperazim\components\plugin\traits\PluginComponentsTrait;

/**
* Class WarpManager
* @package imperazim\warp
*/
final class WarpManager extends PluginComponent {
  use PluginComponentsTrait;

  /**
  * Initializes the warp component.
  * @param PluginToolkit $plugin The Plugin.
  * @return array
  */
  public static function init(PluginToolkit $plugin): array {
    self::setPlugin($plugin);
    self::initializeFiles($plugin);

    return [
      self::COMMAND_COMPONENT => [
        new WarpCommand(),
        new WarpsCommand()
      ]
    ];
  }

  /**
  * Initializes the necessary files.
  * @param PluginToolkit $plugin
  */
  private static function initializeFiles(PluginToolkit $plugin): void {
    $files = [
      'warps' => ['--merge' => ['list' => []]],
      'settings' => ['--merge' => [
        'forms' => [],
        'commands' => [],
        'messages' => [],
      ]]
    ];
    foreach ($files as $fileName => $fileContent) {
      self::setFile(token: $fileName, file: new File(
        directoryOrConfig: $plugin->data,
        fileName: $fileName,
        fileType: File::TYPE_JSON,
        autoGenerate: true,
        readCommand: $fileContent
      ));
    }
  }

  /**
  * Generates a unique token for the warp.
  * @return string
  */
  public static function generateUniqueToken(): string {
    do {
      $token = substr(bin2hex(random_bytes(4)), 0, 8);
    } while (!self::isTokenUnique($token));
    return $token;
  }

  /**
  * Checks if the token data already exists.
  * @param string $token
  * @return bool
  */
  public static function isTokenUnique(string $token): bool {
    return !in_array($token, self::getFile('warps')->get('list', []));
  }

  /**
  * Gets an array of Warp objects.
  * @return Warp[]
  */
  public static function getWarps(): array {
    $warpsList = self::getFile('warps')->get('list', []);
    return array_map(fn($token) => new Warp($token), array_keys($warpsList));
  }

  /**
  * Get a translated message with replaced tags.
  * @param string $messageId The file and message ID in the format 'fileName:messageNested'.
  * @param array $tags The tags to be replaced in the message.
  * @param mixed $defaultValue The default value.
  * @return mixed The translated message with replaced tags.
  */
  public static function getText(string $messageId, array $tags = [], mixed $defaultValue = 'self_messageId'): mixed {
    list($fileName, $messageNested) = explode(':', $messageId);

    if ($defaultValue === 'self_messageId') {
      $defaultValue = $messageId;
    }

    $file = self::getFile($fileName);
    $message = $file->get($messageNested, $defaultValue);

    $tags['up'] = PHP_EOL;
    $tags['line'] = PHP_EOL;

    $message = is_array($message) ? self::replaceTagsInArray($message, $tags) : self::replaceTagsInString($message, $tags);

    if (isset($tags['split'])) {
      $message = explode($tags['split'], $message);
    }

    return $message;
  }

  /**
  * Replaces tags in a string.
  * @param string $message The message containing the tags.
  * @param array $tags The tags to be replaced.
  * @return string The message with the tags replaced.
  */
  private static function replaceTagsInString(string $message, array $tags): string {
    foreach ($tags as $tag => $value) {
      $message = self::replaceRepeatTags($message);
      $message = self::replaceLineBreakTags($message);
      $message = str_replace(['{' . strtoupper($tag) . '}', '{' . strtolower($tag) . '}'], (string) $value . '', (string) $message . '');
    }

    return $message;
  }

  /**
  * Replaces tags in an array of strings.
  * @param array $messages The array of messages.
  * @param array $tags The tags to be replaced.
  * @return array The array with the tags replaced.
  */
  private static function replaceTagsInArray(array $messages, array $tags): array {
    foreach ($messages as &$message) {
      if (is_string($message)) {
        $message = self::replaceTagsInString($message, $tags);
      }
    }

    return $messages;
  }

  /**
  * Replaces all tags [rpt:char=times] in a string with str_repeat(char, times)
  * @param string $message The message containing the tags.
  * @return string The message with the tags replaced.
  */
  public static function replaceRepeatTags(string $message): string {
    return preg_replace_callback('/\[rpt:(.)([-=])(\d+)\]/', function ($matches) {
      return str_repeat($matches[1], (int)$matches[3]);
    }, $message);
  }

  /**
  * Replaces all tags [lb:number=text] in a string with Text::insertLineBreaks(text, number)
  * @param string $message The message containing the tags.
  * @return string The message with the tags replaced.
  */
  public static function replaceLineBreakTags(string $message): string {
    return preg_replace_callback('/\[lb:(\d+)=(.*?)\]/', function ($matches) {
      $number = (int)$matches[1];
      $text = $matches[2];
      return Text::insertLineBreaks($text, $number);
    }, $message);
  }

}