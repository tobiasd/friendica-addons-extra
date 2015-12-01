<?php

/**
 * Name: Planet Chaos
 * Description: Missle Command clone.
 * Version: 1.0
 * Author: Thomas Willingham<http://kakste.com/profile/bouldrake>
 * Status: Unsupported
 */


function planet_chaos_install() {
    register_hook('app_menu', 'addon/planet_chaos/planet_chaos.php', 'planet_chaos_app_menu');
}

function planet_chaos_uninstall() {
    unregister_hook('app_menu', 'addon/planet_chaos/planet_chaos.php', 'planet_chaos_app_menu');

}

function planet_chaos_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="planet_chaos">Planet Chaos</a></div>';
}


function planet_chaos_module() {}

function planet_chaos_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/planet_chaos';

$o .= <<< EOT
<embed src="http://rattyslav.com/games/planetchaos.swf" quality="high" bgcolor="#000000" width="620" height="480" name="planet_chaos" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />

EOT;

return $o;
}
