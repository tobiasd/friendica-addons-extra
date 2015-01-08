<?php

/**
 * Name: villainous_tower_attack
 * Description: Inverted Tower Defence game.  WARNING:  MAJOR time sink.
 * Version: 1.0
 * Author: Thomas Willingham<http://kakste.com/profile/beardyunixer>
 */


function villainous_tower_attack_install() {
    register_hook('app_menu', 'addon/villainous_tower_attack/villainous_tower_attack.php', 'villainous_tower_attack_app_menu');
}

function villainous_tower_attack_uninstall() {
    unregister_hook('app_menu', 'addon/villainous_tower_attack/villainous_tower_attack.php', 'villainous_tower_attack_app_menu');

}

function villainous_tower_attack_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="villainous_tower_attack">Villainous Tower Attack</a></div>';
}


function villainous_tower_attack_module() {}

function villainous_tower_attack_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/villainous_tower_attack';

$o .= <<< EOT
<embed src="http://rattyslav.com/games/villainous_tower_attack.swf" quality="high" bgcolor="#000000" width="800" height="600" name="villainous_tower_attack" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />

EOT;

return $o;
}
