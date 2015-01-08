<?php

/**
 * Name: Gold Strike
 * Description: Throw your pick at the gold blocks to score points. Only the gold blocks will give you points. Strategy is the key to this online miner game game.

 * Version: 1.0
 * Author: Holger Froese
 */


function goldstrike_install() {
    register_hook('app_menu', 'addon/goldstrike/goldstrike.php', 'goldstrike_app_menu');
}

function goldstrike_uninstall() {
    unregister_hook('app_menu', 'addon/goldstrike/goldstrike.php', 'goldstrike_app_menu');

}

function goldstrike_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="goldstrike">Gold Strike</a></div>';
}


function goldstrike_module() {}

function goldstrike_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/goldstrike';

$o .= <<< EOT
<br><br>
<p align="left">
<embed src="addon/goldstrike/goldstrike.swf" quality="high" bgcolor="#000000" width="900" height="650" name="goldstrike" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
<br><br>
<b>Gold Strike - Throw your pick at the gold blocks to score points. Only the gold blocks will give you points. Strategy is the key to this online miner game game.<br>
</p>
EOT;

return $o;
}