<?php

/**
 * Name: Winter Bells
 * Description: Help the bunny to jump over the soft ice bells of Christmas. A Christmas game completely dedicated to a rabbit. The game is simple and immediate and just very little to reach high altitudes.
 * Version: 1.0
 * Author: Holger Froese
 */


function winter_bells_install() {
    register_hook('app_menu', 'addon/winter_bells/winter_bells.php', 'winter_bells_app_menu');
}

function winter_bells_uninstall() {
    unregister_hook('app_menu', 'addon/winter_bells/winter_bells.php', 'winter_bells_app_menu');

}

function winter_bells_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="winter_bells">Winter Bells</a></div>';
}


function winter_bells_module() {}

function winter_bells_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/winter_bells';

$o .= <<< EOT
<br><br>
<p align="left">
<embed src="addon/winter_bells/winter_bells.swf" quality="high" bgcolor="#000000" width="750" height="500" name="winter_bells" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
<br><br>
<b>Help the bunny to jump over the soft ice bells of Christmas. A Christmas game completely dedicated to a rabbit. The game is simple and immediate and just very little to reach high altitudes. .</b><br>
</p>
EOT;

return $o;
}