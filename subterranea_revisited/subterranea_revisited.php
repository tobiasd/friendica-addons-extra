<?php

/**
 * Name: Subterranea Revisited
 * Description: Platformer, slightly reminiscient of Jet Set Willy
 * Version: 1.0
 * Author: Thomas Willingham<http://kakste.com/profile/bouldrake>
 * Status: Unsupported
 */


function subterranea_revisited_install() {
    register_hook('app_menu', 'addon/subterranea_revisited/subterranea_revisited.php', 'subterranea_revisited_app_menu');
}

function subterranea_revisited_uninstall() {
    unregister_hook('app_menu', 'addon/subterranea_revisited/subterranea_revisited.php', 'subterranea_revisited_app_menu');

}

function subterranea_revisited_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="subterranea_revisited">Subterranea Revisited</a></div>';
}


function subterranea_revisited_module() {}

function subterranea_revisited_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/subterranea_revisited';

$o .= <<< EOT
<embed src="http://rattyslav.com/games/Subterranea.swf" quality="high" bgcolor="#000000" width="620" height="600" name="subterranea_revisited" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />

EOT;

return $o;
}
