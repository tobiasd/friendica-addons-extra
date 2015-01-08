<?php

/**
 * Name: Pong
 * Description: Mutliplayer pong for the free web.
 * Version: 1.0
 * Author: Thomas Willingham<http://kakste.com/profile/beardyunixer>
 */


function pong_install() {
    register_hook('app_menu', 'addon/pong/pong.php', 'pong_app_menu');
}

function pong_uninstall() {
    unregister_hook('app_menu', 'addon/pong/pong.php', 'pong_app_menu');

}

function pong_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="pong">Pong</a></div>';
}


function pong_module() {}

function pong_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/pong';

$o .= <<< EOT
<embed src="$baseurl/pong.swf" quality="high" bgcolor="#000000" width="620" height="480" name="pong" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />

EOT;

return $o;
}
