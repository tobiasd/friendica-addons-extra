<?php
/**
* Name: Apps Menu
* Description: Apps Menu
* Version: 1.0
* Author: Thomas Willingham <https://kakste.com/profile/beardyunixer>
*/

function apps_install() {
register_hook('app_menu', 'addon/apps/apps.php', 'apps_app_menu');
}

function apps_uninstall() {
unregister_hook('app_menu', 'addon/apps/apps.php', 'apps_app_menu');

}

function apps_app_menu($a,&$b) {
$b['app_menu'][] = '<div class="app-title"><a href="apps">' . t('Test Addon') . '</a></div>';
}


function apps_module() {
return;
}


function apps_content(&$a) {
$baseurl = $a->get_baseurl() . '/addon/apps';
$o = '';

 

if(file_exists('addon/apps/apps.html'))
 		$o .= file_get_contents('addon/apps/apps.html');


EOT;
return $o;
    
}
