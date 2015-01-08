<?php

/**
 * Name: Word Chain Plus
 * Description: Ridiculously addictive word game that doesn't spam your friends
 * Version: 1.0
 * Author: Thomas Willingham<http://kakste.com/profile/beardyunixer>
 */


function word_chain_plus_install() {
    register_hook('app_menu', 'addon/word_chain_plus/word_chain_plus.php', 'word_chain_plus_app_menu');
}

function word_chain_plus_uninstall() {
    unregister_hook('app_menu', 'addon/word_chain_plus/word_chain_plus.php', 'word_chain_plus_app_menu');

}

function word_chain_plus_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="word_chain_plus">Word Chain Plus</a></div>';
}


function word_chain_plus_module() {}

function word_chain_plus_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/word_chain_plus';

$o .= <<< EOT
<embed width="640" height="480" src="http://rattyslav.com/games/wordchainplus.swf" type="application/x-shockwave-flash"></embed>
EOT;

return $o;
}
