<?php
/**
* Name: Groups Page
* Description: add a groups page
* Version: 1.0
* Author: Thomas Willingham
*/

function groups_install() {
register_hook('app_menu', 'addon/groups/groups.php', 'groups_app_menu');
}

function groups_uninstall() {
unregister_hook('app_menu', 'addon/groups/groups.php', 'groups_app_menu');

}

function groups_app_menu($a,&$b) {
$b['app_menu'][] = '<div class="app-title"><a href="groups">' . t('Groups') . '</a></div>';
}


function groups_module() {
return;
}


function groups_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/groups';
$o = '';

$a->page['htmlhead'] .= '<link rel="stylesheet" type="text/css" href="'.$a->get_baseurl().'/addon/groups/zero-fix.css"/>';


  $o .= <<< EOT

<iframe src="http://dir.friendica.com/directory/forum" width="1024" height="768"></iframe>



EOT;
return $o;
    
}