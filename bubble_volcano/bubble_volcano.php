<?php

/**
 * Bubble Volcano
 * 
 *
 * Name: Bubble Volcano
 * Description: Interesting Twist on Bust A Move.
 * Version: 1.0
 * Author: Thomas Willingham<http://kakste.com/profile/bouldrake>
 * Status: Unsupported
 */


function bubble_volcano_install() {
    register_hook('app_menu', 'addon/bubble_volcano/bubble_volcano.php', 'bubble_volcano_app_menu');
}

function bubble_volcano_uninstall() {
    unregister_hook('app_menu', 'addon/bubble_volcano/bubble_volcano.php', 'bubble_volcano_app_menu');

}

function bubble_volcano_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="bubble_volcano">Bubble Volcano</a></div>';
}


function bubble_volcano_module() {}

function bubble_volcano_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/bubble_volcano';

$o .= <<< EOT
<embed src="http://rattyslav.com/games/BubbleVolcano.swf" quality="high" bgcolor="#000000" width="620" height="480" name="bubble_volcano" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />

EOT;

return $o;
}
