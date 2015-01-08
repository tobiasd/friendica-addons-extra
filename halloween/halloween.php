<?php

/**
 * Name: Out Of Halloween
 * Description: You have to kill all pumpkins. Also be careful about ghosts.
 * Version: 1.0
 * Author: Holger Froese
 */


function halloween_install() {
    register_hook('app_menu', 'addon/halloween/halloween.php', 'halloween_app_menu');
}

function halloween_uninstall() {
    unregister_hook('app_menu', 'addon/halloween/halloween.php', 'halloween_app_menu');

}

function halloween_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="halloween">Beer Monster</a></div>';
}


function halloween_module() {}

function halloween_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/halloween';

$o .= <<< EOT
<br><br>
<p align="left">
<embed src="addon/halloween/halloween.swf" quality="high" bgcolor="#FFFFFF" width="900" height="550" name="halloween" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
<br><br>
<b>You have to kill all pumpkins. Also be careful about ghosts.</b><br>
</p>
EOT;

return $o;
}