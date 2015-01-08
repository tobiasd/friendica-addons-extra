<?php
/**
* Name: Can I Cheat Death?
* Description: Can I Cheat Death
* Version: 1.0
* Author: Thomas Willingham
*/

function canicheatdeath_install() {
register_hook('app_menu', 'addon/canicheatdeath/canicheatdeath.php', 'canicheatdeath_app_menu');
}

function canicheatdeath_uninstall() {
unregister_hook('app_menu', 'addon/canicheatdeath/canicheatdeath.php', 'canicheatdeath_app_menu');

}

function canicheatdeath_app_menu($a,&$b) {
$b['app_menu'][] = '<div class="app-title"><a href="canicheatdeath">' . t('Can I Cheat Death?') . '</a></div>';
}


function canicheatdeath_module() {
return;
}


function canicheatdeath_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/canicheatdeath';
$o = '';
$a->page['htmlhead'] .= '<link rel="stylesheet" type="text/css" href="'.$a->get_baseurl().'/addon/canicheatdeath/canicheatdeath.css"/>';
  $o .= <<< EOT

<iframe src="http://arcade.kakste.com/CPC/canicheatdeath.html" width="860" height="520"></iframe>


EOT;
return $o;
    
}