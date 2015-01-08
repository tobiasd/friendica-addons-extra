<?php
/**
* Name: Mathletics Plugin
* Description: add mathletics
* Version: 1.0
* Author: Thomas Willingham
*/

function mathletics_install() {
register_hook('app_menu', 'addon/mathletics/mathletics.php', 'mathletics_app_menu');
}

function mathletics_uninstall() {
unregister_hook('app_menu', 'addon/mathletics/mathletics.php', 'mathletics_app_menu');

}

function mathletics_app_menu($a,&$b) {
$b['app_menu'][] = '<div class="app-title"><a href="mathletics">' . t('Mathletics') . '</a></div>';
}


function mathletics_module() {
return;
}


function mathletics_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/mathletics';
$o = '';

$a->page['htmlhead'] .= '<link rel="stylesheet" type="text/css" href="'.$a->get_baseurl().'/addon/mathletics/mathletics.css"/>';


  $o .= <<< EOT

<iframe src="http://mathletics.co.uk" width="1024" height="800"></iframe>





EOT;
return $o;
    
}