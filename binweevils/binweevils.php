<?php
/**
* Name: Bin Weevils
* Description: Embeds BinWeevils
* Version: 1.0
* Author: Thomas Willingham
*/

function binweevils_install() {
register_hook('app_menu', 'addon/binweevils/binweevils.php', 'binweevils_app_menu');
}

function binweevils_uninstall() {
unregister_hook('app_menu', 'addon/binweevils/binweevils.php', 'binweevils_app_menu');

}

function binweevils_app_menu($a,&$b) {
$b['app_menu'][] = '<div class="app-title"><a href="binweevils">' . t('BinWeevils') . '</a></div>';
}


function binweevils_module() {
return;
}


function binweevils_content(&$a) {
$baseurl = $a->get_baseurl() . '/addon/binweevils';
$o = '';

$a->page['htmlhead'] .= '<link rel="stylesheet" type="text/css" href="'.$a->get_baseurl().'/addon/binweevils/binweevils.css"/>';





  $o .= <<< EOT

<iframe src="http://www.binweevils.com" width="1024" height="800"></iframe>


EOT;
return $o;
    
}
