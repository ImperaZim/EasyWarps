# <h3 align="center">EasyWarps v2.0.0</h3>
<div align="center">
  
![EasyWarps](https://images-ext-1.discordapp.net/external/-B4gyhX3tHZMWZRgyDRGmNoucDM_b6LDyuc05tzEWyA/https/repository-images.githubusercontent.com/547500502/9cb24fee-b7a4-48eb-a860-359bc8f72f20)

</div>

<p align="center">A simple plugin to set teleport points on worlds to make it easier for players to get around!.</p>

- - - -
## Download
**[PHAR](https://imperazim.cloud/plugins/download/?plugin=easywarps)**

- - - -
## Software support
**[EasyWarps](https://github.com/ImperaZim/EasyWarps)** is a PHP plugin for **[PocketMine-MP](https://github.com/pmmp/PocketMine-MP )**, designed specifically for versions 5.0 and higher.  It may not work on older versions or other webserver APIs! 

## For developers
 The plugin provides some public events to be used in other plugins such as:
-  [WarpCreateEvent](https://github.com/ImperaZim/EasyWarps/blob/main/src/ImperaZim/EasyWarps/events/WarpCreateEvent.php): **Always** called when an admin creates a new warp.
-  [WarpDeleteEvent](https://github.com/ImperaZim/EasyWarps/blob/main/src/ImperaZim/EasyWarps/events/WarpDeleteEvent.php): **Always** called when an admin deletes a warp.
-  [WarpTeleportEvent](https://github.com/ImperaZim/EasyWarps/blob/main/src/ImperaZim/EasyWarps/events/WarpTeleportEvent.php): **Always** called when a player teleports to a wrap.