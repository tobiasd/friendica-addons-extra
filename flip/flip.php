<?php
/**
* Name: Flip me
* Description: Flip your text
* Version: 1.0
* Author: Zen @TokTan.Org
*/

function flip_install() {
register_hook('app_menu', 'addon/flip/flip.php', 'flip_app_menu');
}

function flip_uninstall() {
unregister_hook('app_menu', 'addon/flip/flip.php', 'flip_app_menu');

}

function flip_app_menu($a,&$b) {
$b['app_menu'][] = '<div class="app-title"><a href="flip">' . t('Flip the text') . '</a></div>';
}


function flip_module() {
return;
}


function flip_content(&$a) {
$baseurl = $a->get_baseurl() . '/addon/flip';
$o = '';

$a->page['htmlhead'] .= '<link rel="stylesheet" type="text/css" href="'.$a->get_baseurl().'/addon/flip/flip.css"/>';


$baseurl = $a->get_baseurl();


  $o .= <<< EOT

<iframe src ="addon/flip/index.html" height="800" width="650">




EOT;
return $o;
    
}