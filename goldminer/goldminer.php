<?php

/**
 * Name: Goldminer
 * Description: Collect enough gold quick enough to reach the next level.

 * Version: 1.0
 * Author: Holger Froese
 */


function goldminer_install() {
    register_hook('app_menu', 'addon/goldminer/goldminer.php', 'goldminer_app_menu');
}

function goldminer_uninstall() {
    unregister_hook('app_menu', 'addon/goldminer/goldminer.php', 'goldminer_app_menu');

}

function goldminer_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="goldminer">The Idiot Test 2</a></div>';
}


function goldminer_module() {}

function goldminer_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/goldminer';

$o .= <<< EOT
<br><br>
<p align="left">
<embed src="addon/goldminer/goldminer.swf" quality="high" bgcolor="#000000" width="900" height="650" name="goldminer" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
<br><br>
<b>Collect enough gold quick enough to reach the next level.<br>
</p>
EOT;

return $o;
}