<?php

/**
 * Name: Space Invaders
 * Description: Can you beleive we lived this long without it?
 * Version: 1.0
 * Author: Thomas Willingham<http://kakste.com/profile/beardyunixer>
 */


function space_invaders_install() {
    register_hook('app_menu', 'addon/space_invaders/space_invaders.php', 'space_invaders_app_menu');
}

function space_invaders_uninstall() {
    unregister_hook('app_menu', 'addon/space_invaders/space_invaders.php', 'space_invaders_app_menu');

}

function space_invaders_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="space_invaders">Space Invaders</a></div>';
}


function space_invaders_module() {}

function space_invaders_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/space_invaders';

$o .= <<< EOT
<embed src="http://rattyslav.com/games/spaceinvaderz.swf" quality="high" bgcolor="#000000" width="620" height="480" name="space_invaders" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />

EOT;

return $o;
}
