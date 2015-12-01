<?php

/**
 * Name: Nyan Cat
 * Description: The one and only, much beloved Nyan Cat
 * Version: 1.0
 * Author: Thomas Willingham<http://kakste.com/profile/beardyunixer>
 * Status: Unsupported
 */


function nyan_cat_install() {
    register_hook('app_menu', 'addon/nyan_cat/nyan_cat.php', 'nyan_cat_app_menu');
}

function nyan_cat_uninstall() {
    unregister_hook('app_menu', 'addon/nyan_cat/nyan_cat.php', 'nyan_cat_app_menu');

}

function nyan_cat_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="nyan_cat">Nyan Cat</a></div>';
}


function nyan_cat_module() {}

function nyan_cat_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/nyan_cat';

$o .= <<< EOT
<embed width="640" height="480" src="http://rattyslav.com/games/nyancat.swf" type="application/x-shockwave-flash"></embed>
EOT;

return $o;
}
