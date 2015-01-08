<?php

/**
 * Name: farmmania
 * Description: Retarded Imaginary Farm for Friendica
 * Version: 1.0
 * Author: Thomas Willingham<http://kakste.com/profile/beardyunixer>
 */


function farmmania_install() {
    register_hook('app_menu', 'addon/farmmania/farmmania.php', 'farmmania_app_menu');
}

function farmmania_uninstall() {
    unregister_hook('app_menu', 'addon/farmmania/farmmania.php', 'farmmania_app_menu');

}

function farmmania_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="farmmania">farmmania</a></div>';
}


function farmmania_module() {}

function farmmania_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/farmmania';

$o .= <<< EOT
<embed src="http://rattylsav.com/games/farmmania.swf" quality="high" bgcolor="#000000" width="640" height="480" name="farmmania" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />

EOT;

return $o;
}
