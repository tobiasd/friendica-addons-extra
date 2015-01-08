<?php
/**
* Name: Urban Dead
* Description: Embeds Urban Dead as an app
* Version: 1.0
* Author: Thomas Willingham
*/

function urban_dead_install() {
register_hook('app_menu', 'addon/urban_dead/urban_dead.php', 'urban_dead_app_menu');
}

function urban_dead_uninstall() {
unregister_hook('app_menu', 'addon/urban_dead/urban_dead.php', 'urban_dead_app_menu');

}

function urban_dead_app_menu($a,&$b) {
$b['app_menu'][] = '<div class="app-title"><a href="urban_dead">' . t('Urban Dead') . '</a></div>';
}


function urban_dead_module() {
return;
}


function urban_dead_content(&$a) {
$baseurl = $a->get_baseurl() . '/addon/urban_dead';
$o = '';

$a->page['htmlhead'] .= '<link rel="stylesheet" type="text/css" href="'.$a->get_baseurl().'/addon/urban_dead/urban_dead.css"/>';





  $o .= <<< EOT

<iframe src="http://urbandead.com" width="1024" height="800"></iframe>



<p>Please note, this is a third party website, it just happens to be a very spiffy one.</a></p>

EOT;
return $o;
    
}
