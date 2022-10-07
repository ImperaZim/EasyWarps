<?php

namespace ImperaZim\EasyWarps\Utils\Form;

use pocketmine\plugin\PluginBase;
use ImperaZim\EasyWarps\Utils\Form\types\ModalForm;
use ImperaZim\EasyWarps\Utils\Form\types\CustomForm;
use ImperaZim\EasyWarps\Utils\Form\types\SimpleForm;

class FormAPI {
 
 public static function createCustomForm(?callable $function = null) : CustomForm {
  return new CustomForm($function);
 }

 public static function createSimpleForm(?callable $function = null) : SimpleForm {
  return new SimpleForm($function);
 }

 public static function createModalForm(?callable $function = null) : ModalForm {
  return new ModalForm($function);
 }

} 
