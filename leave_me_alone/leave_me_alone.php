<?php

/**
 * Leave me alone
 * Simple, but addictive, flash game.
 *
 * Name: Leave Me Alone
 * Description: Simple, but addictive flash game.
 * Version: 1.0
 * Author: Thomas Willingham<http://kakste.com/profile/bouldrake>
 */


function leave_me_alone_install() {
    register_hook('app_menu', 'addon/leave_me_alone/leave_me_alone.php', 'leave_me_alone_app_menu');
}

function leave_me_alone_uninstall() {
    unregister_hook('app_menu', 'addon/leave_me_alone/leave_me_alone.php', 'leave_me_alone_app_menu');

}

function leave_me_alone_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="leave_me_alone">Infectonator</a></div>';
}


function leave_me_alone_module() {}

function leave_me_alone_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/leave_me_alone';

$o .= <<< EOT
<embed src="http://rattyslav.com/games/LeaveMeAlone.swf" quality="high" bgcolor="#000000" width="620" height="480" name="leave_me_alone" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />

EOT;

return $o;
}
