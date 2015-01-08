<?php
/**
* Name: Dungeons And Treasures
* Description: Embeds Dungeons And Treasures
* Version: 1.0
* Author: Thomas Willingham
*/

function dungeons_and_treasures_install() {
register_hook('app_menu', 'addon/dungeons_and_treasures/dungeons_and_treasures.php', 'dungeons_and_treasures_app_menu');
}

function dungeons_and_treasures_uninstall() {
unregister_hook('app_menu', 'addon/dungeons_and_treasures/dungeons_and_treasures.php', 'dungeons_and_treasures_app_menu');

}

function dungeons_and_treasures_app_menu($a,&$b) {
$b['app_menu'][] = '<div class="app-title"><a href="dungeons_and_treasures">' . t('Dungeons And Treasures') . '</a></div>';
}


function dungeons_and_treasures_module() {
return;
}


function dungeons_and_treasures_content(&$a) {
$baseurl = $a->get_baseurl() . '/addon/dungeons_and_treasures';
$o = '';

$a->page['htmlhead'] .= '<link rel="stylesheet" type="text/css" href="'.$a->get_baseurl().'/addon/dungeons_and_treasures/dungeons_and_treasures.css"/>';





  $o .= <<< EOT

<iframe src="http://www.dungeons-treasures.com/index.php?c=1&p=6528" width="1024" height="1460"></iframe>



<p>Please note, this is a third party website, it just happens to be a very spiffy one.</a></p>

EOT;
return $o;
    
}
