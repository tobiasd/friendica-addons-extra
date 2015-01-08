<?php

/**
 * Name: Simon
 * Description: Simon - The Memory Retention Game.
 * Version: 1.0
 * Author: Holger Froese
 */


function simon_install() {
    register_hook('app_menu', 'addon/simon/simon.php', 'simon_app_menu');
}

function simon_uninstall() {
    unregister_hook('app_menu', 'addon/simon/simon.php', 'simon_app_menu');

}

function simon_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="simon">Simon</a></div>';
}


function simon_module() {}

function simon_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/simon';

$o .= <<< EOT
<br><br>
<p align="left">
<embed src="addon/simon/simon.swf" quality="high" bgcolor="gray" width="800" height="600" name="simon" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
<br><br>
<b>- Simon Games are traditionally known in the form of toys. This version of the game fulfills the same objective; to challenge an in a way measure our memory retention capacity by generating a growing sequence of events, in this case colors and sounds, that the player has to repeat. How many sequential events can you remember ? - Simon will tell you.<br>

- Once the game Simon is loaded click on play, be alert and observe what color lights up... click on it (1).   <br>
- The game simon continues by lighting up the clicked color and an additional one (2). Now you have to click on both colors in the same order (1)-(2). <br>
- Simon continues adding and/or repeating new colors (and sounds) that you must memorize and repeat in the same order, until your memory allows it - and / or until you make a mistake :-(<br>
- Click menu to begin a new Simon Game.<br>
- The SCORE counts the amount of colors (or sounds) that you have memorized. </b><br>
</p>
EOT;

return $o;
}