<?php

/**
 * infectonator2.
 * This is my favourite flash game ever, but note it contains an ad.
 *
 * Name: infectonator2
 * Description: Zombie Invasion Strategy Game
 * Version: 1.0
 * Author: Thomas Willingham<http://kakste.com/profile/bouldrake>
 * Status: Unsupported
 */


function infectonator2_install() {
    register_hook('app_menu', 'addon/infectonator2/infectonator2.php', 'infectonator2_app_menu');
}

function infectonator2_uninstall() {
    unregister_hook('app_menu', 'addon/infectonator2/infectonator2.php', 'infectonator2_app_menu');

}

function infectonator2_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="infectonator2">infectonator2</a></div>';
}


function infectonator2_module() {}

function infectonator2_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/infectonator2';

$o .= <<< EOT
<embed src="http://rattyslav.com/games/Infectonator2.swf" quality="high" bgcolor="#000000" width="620" height="480" name="infectonator2" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />

EOT;

return $o;
}
