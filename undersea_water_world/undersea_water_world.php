<?php

/**
 * Name: Undersea Water World
 * Description: Kids dress up game.
 * Version: 1.0
 * Author: Thomas Willingham<http://kakste.com/profile/bouldrake>
 */


function undersea_water_world_install() {
    register_hook('app_menu', 'addon/undersea_water_world/undersea_water_world.php', 'undersea_water_world_app_menu');
}

function undersea_water_world_uninstall() {
    unregister_hook('app_menu', 'addon/undersea_water_world/undersea_water_world.php', 'undersea_water_world_app_menu');

}

function undersea_water_world_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="undersea_water_world">Undersea Water World</a></div>';
}


function undersea_water_world_module() {}

function undersea_water_world_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/undersea_water_world';

$o .= <<< EOT
<embed src="http://arcade.kakste.com/wp-content/games/undersea-wonder-world-build-up.swf?affiliate_id=7626254991a0161f" quality="high" bgcolor="#000000" width="620" height="480" name="undersea_water_world" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />

EOT;

return $o;
}
