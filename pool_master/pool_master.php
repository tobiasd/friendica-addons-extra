<?php
/**
* Name: Pool Master
* Description: Pool game for Friendica
* Version: 1.0
* Author: Thomas Willingham
*/

function pool_master_install() {
register_hook('app_menu', 'addon/pool_master/pool_master.php', 'pool_master_app_menu');
}

function pool_master_uninstall() {
unregister_hook('app_menu', 'addon/pool_master/pool_master.php', 'pool_master_app_menu');

}

function pool_master_app_menu($a,&$b) {
$b['app_menu'][] = '<div class="app-title"><a href="pool_master">' . t('Pool Master') . '</a></div>';
}


function pool_master_module() {
return;
}


function pool_master_content(&$a) {
$baseurl = $a->get_baseurl() . '/addon/pool_master';
$o = '';

$a->page['htmlhead'] .= '<link rel="stylesheet" type="text/css" href="'.$a->get_baseurl().'/addon/pool_master/pool_master.css"/>';





  $o .= <<< EOT

<iframe src="http://rattyslav.com/games/PoolMaster.swf" width="1024" height="800"></iframe>




EOT;
return $o;
    
}
