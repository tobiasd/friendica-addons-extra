<?php

/**
 * Infectonator.
 * This is my favourite flash game ever, but note it contains an ad.
 *
 * Name: Infectonator
 * Description: Zombie Invasion Strategy Game
 * Version: 1.0
 * Author: Thomas Willingham<http://kakste.com/profile/bouldrake>
 */


function infectonator_install() {
    register_hook('app_menu', 'addon/infectonator/infectonator.php', 'infectonator_app_menu');
}

function infectonator_uninstall() {
    unregister_hook('app_menu', 'addon/infectonator/infectonator.php', 'infectonator_app_menu');

}

function infectonator_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="infectonator">Infectonator</a></div>';
}


function infectonator_module() {}

function infectonator_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/infectonator';

$o .= <<< EOT
<embed src="http://rattyslav.com/games/infectonator.swf" quality="high" bgcolor="#000000" width="620" height="480" name="infectonator" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />

EOT;

return $o;
}
