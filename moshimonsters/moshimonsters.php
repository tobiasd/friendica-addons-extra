<?php
/**
* Name: MoshiMonsters
* Description: Embeds Moshimonsters
* Version: 1.0
* Author: Thomas Willingham
*/

function moshimonsters_install() {
register_hook('app_menu', 'addon/moshimonsters/moshimonsters.php', 'moshimonsters_app_menu');
}

function moshimonsters_uninstall() {
unregister_hook('app_menu', 'addon/moshimonsters/moshimonsters.php', 'moshimonsters_app_menu');

}

function moshimonsters_app_menu($a,&$b) {
$b['app_menu'][] = '<div class="app-title"><a href="moshimonsters">' . t('Moshi Monsters') . '</a></div>';
}


function moshimonsters_module() {
return;
}


function moshimonsters_content(&$a) {
$baseurl = $a->get_baseurl() . '/addon/moshimonsters';
$o = '';

$a->page['htmlhead'] .= '<link rel="stylesheet" type="text/css" href="'.$a->get_baseurl().'/addon/moshimonsters/moshimonsters.css"/>';





  $o .= <<< EOT

<iframe src="http://www.moshimonsters.com" width="1024" height="800"></iframe>


EOT;
return $o;
    
}
