<?php

/**
 * Name: Trackball
 * Description: Move the ball along the track, by balancing the track, moving your cursor across the arrowbuttons.
 * Version: 1.0
 * Author: Holger Froese
 */


function trackball_install() {
    register_hook('app_menu', 'addon/trackball/trackball.php', 'trackball_app_menu');
}

function trackball_uninstall() {
    unregister_hook('app_menu', 'addon/trackball/trackball.php', 'trackball_app_menu');

}

function trackball_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="trackball">Trackball</a></div>';
}


function trackball_module() {}

function trackball_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/trackball';

$o .= <<< EOT
<br><br>
<p align="left">
<embed src="addon/trackball/trackball.swf" quality="high" bgcolor="#FFFFFF" width="900" height="600" name="trackball" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
<br><br>
<b> Move the ball along the track, by balancing the track, moving your cursor across the arrowbuttons.</b><br>
</p>
EOT;

return $o;
}