<?php
/**
* Name: We Know What You're Doing!
* Description: Miss Facebook privacy abuse?  Add it to Friendica!
* Version: 1.0
* Author: Thomas Willingham
*/

function weknowwhatyouredoing_install() {
register_hook('app_menu', 'addon/weknowwhatyouredoing/weknowwhatyouredoing.php', 'weknowwhatyouredoing_app_menu');
}

function weknowwhatyouredoing_uninstall() {
unregister_hook('app_menu', 'addon/weknowwhatyouredoing/weknowwhatyouredoing.php', 'weknowwhatyouredoing_app_menu');

}

function weknowwhatyouredoing_app_menu($a,&$b) {
$b['app_menu'][] = '<div class="app-title"><a href="weknowwhatyouredoing">' . t('We Know What You Are Doing') . '</a></div>';
}


function weknowwhatyouredoing_module() {
return;
}


function weknowwhatyouredoing_content(&$a) {
$baseurl = $a->get_baseurl() . '/addon/weknowwhatyouredoing';
$o = '';

$a->page['htmlhead'] .= '<link rel="stylesheet" type="text/css" href="'.$a->get_baseurl().'/addon/weknowwhatyouredoing/weknowwhatyouredoing.css"/>';





  $o .= <<< EOT

<iframe src="http://weknowwhatyouredoing.com" width="1024" height="800"></iframe>





EOT;
return $o;
    
}
