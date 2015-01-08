<?php

/**
 * Name: Slingshot Santa
 * Description: Throw Santa as far as you can. Play SlingShot Santa and produce your own data analysis to review and rate performance!
 * Version: 1.0
 * Author: Holger Froese
 */


function slingshot_santa_install() {
    register_hook('app_menu', 'addon/slingshot_santa/slingshot_santa.php', 'slingshot_santa_app_menu');
}

function slingshot_santa_uninstall() {
    unregister_hook('app_menu', 'addon/slingshot_santa/slingshot_santa.php', 'slingshot_santa_app_menu');

}

function slingshot_santa_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="slingshot_santa">Slingshot Santa</a></div>';
}


function slingshot_santa_module() {}

function slingshot_santa_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/slingshot_santa';

$o .= <<< EOT
<br><br>
<p align="left">
<embed src="addon/slingshot_santa/slingshot_santa.swf" quality="high" bgcolor="#000000" width="900" height="510" name="slingshot_santa" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
<br><br>
<b>How to Play:</b> Use your computer mouse to control and start the slingshot by clicking on the game screen. The slingshot will start to move.<br>
After you reach your desired speed, click your mouse and hold it. The longer you hold the mouse, the further you shoot Santa. Be careful as too<br>
much pressure is not good either, as it cause Santa to fall on the ground. To shoot Santa across the gorge, release your mouse button. Make sure<br>
to release the button before you get too close to the edge. To try again for a better score, click on the '<b>THROW AGAIN</b>' button on the screen.<br>
[<i><b>Note - the 'Upload your score' feature - created by the original authors of the game - doesn't work</i></b>]</p>
EOT;

return $o;
}