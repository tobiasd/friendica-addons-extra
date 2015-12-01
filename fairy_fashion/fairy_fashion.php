<?php
/**
* Name: Fairy Fashion
* Description: Kids Dressup Game
* Version: 1.0
* Author: Thomas Willingham
* Status: Unsupported
*/

function fairy_fashion_install() {
register_hook('app_menu', 'addon/fairy_fashion/fairy_fashion.php', 'fairy_fashion_app_menu');
}

function fairy_fashion_uninstall() {
unregister_hook('app_menu', 'addon/fairy_fashion/fairy_fashion.php', 'fairy_fashion_app_menu');

}

function fairy_fashion_app_menu($a,&$b) {
$b['app_menu'][] = '<div class="app-title"><a href="fairy_fashion">' . t('Fairy Fashion') . '</a></div>';
}


function fairy_fashion_module() {
return;
}


function fairy_fashion_content(&$a) {
$baseurl = $a->get_baseurl() . '/addon/fairy_fashion';
$o = '';

$a->page['htmlhead'] .= '<link rel="stylesheet" type="text/css" href="'.$a->get_baseurl().'/addon/fairy_fashion/fairy_fashion.css"/>';





  $o .= <<< EOT

<iframe src="http://arcade.kakste.com/fairy-fashion/" width="1024" height="800"></iframe>




EOT;
return $o;
    
}
