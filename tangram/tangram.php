<?php

/**
 * Name: Tangram
 * Description: Object of the game is to compile geometric figures from seven basic pieces.
 * Version: 1.0
 * Author: Holger Froese
 */


function tangram_install() {
    register_hook('app_menu', 'addon/tangram/tangram.php', 'tangram_app_menu');
}

function tangram_uninstall() {
    unregister_hook('app_menu', 'addon/tangram/tangram.php', 'tangram_app_menu');

}

function tangram_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="tangram">Tangram</a></div>';
}


function tangram_module() {}

function tangram_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/tangram';

$o .= <<< EOT
<br><br>
<p align="left">
<embed src="addon/tangram/tangram.swf" quality="high" bgcolor="#FFFFFF" width="600" height="600" name="tangram" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
<br><br>
<b>Object of the game is to compile geometric figures from seven basic pieces.</b><br>
</p>
EOT;

return $o;
}