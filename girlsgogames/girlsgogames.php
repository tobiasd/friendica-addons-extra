<?php
/**
* Name: GirlsGoGames
* Description: Embeds GirlsGoGames
* Version: 1.0
* Author: Thomas Willingham
*/

function girlsgogames_install() {
register_hook('app_menu', 'addon/girlsgogames/girlsgogames.php', 'girlsgogames_app_menu');
}

function girlsgogames_uninstall() {
unregister_hook('app_menu', 'addon/girlsgogames/girlsgogames.php', 'girlsgogames_app_menu');

}

function girlsgogames_app_menu($a,&$b) {
$b['app_menu'][] = '<div class="app-title"><a href="girlsgogames">' . t('GirlsGoGames') . '</a></div>';
}


function girlsgogames_module() {
return;
}


function girlsgogames_content(&$a) {
$baseurl = $a->get_baseurl() . '/addon/girlsgogames';
$o = '';

$a->page['htmlhead'] .= '<link rel="stylesheet" type="text/css" href="'.$a->get_baseurl().'/addon/girlsgogames/girlsgogames.css"/>';





  $o .= <<< EOT

<iframe src="http://www.girlsgogames.com" width="1024" height="800"></iframe>


EOT;
return $o;
    
}
