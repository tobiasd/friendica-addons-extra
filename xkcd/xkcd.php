<?php
/**
* Name: XKCD
* Description: add XKCD
* Version: 1.0
* Author: Thomas Willingham
*/

function xkcd_install() {
register_hook('app_menu', 'addon/xkcd/xkcd.php', 'xkcd_app_menu');
}

function xkcd_uninstall() {
unregister_hook('app_menu', 'addon/xkcd/xkcd.php', 'xkcd_app_menu');

}

function xkcd_app_menu($a,&$b) {
$b['app_menu'][] = '<div class="app-title"><a href="xkcd">' . t('XKCD') . '</a></div>';
}


function xkcd_module() {
return;
}


function xkcd_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/xkcd';
$o = '';

$a->page['htmlhead'] .= '<link rel="stylesheet" type="text/css" href="'.$a->get_baseurl().'/addon/xkcd/zero-fix.css"/>';

  $o .= <<< EOT

<iframe src="http://m.xkcd.com" width="800" height="600"></iframe>


EOT;
return $o;
    
}