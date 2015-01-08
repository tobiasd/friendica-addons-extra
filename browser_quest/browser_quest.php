<?php
/**
* Name: Browser Quest
* Description: Embeds Browser Quest
* Version: 1.0
* Author: Thomas Willingham
*/

function browser_quest_install() {
register_hook('app_menu', 'addon/browser_quest/browser_quest.php', 'browser_quest_app_menu');
}

function browser_quest_uninstall() {
unregister_hook('app_menu', 'addon/browser_quest/browser_quest.php', 'browser_quest_app_menu');

}

function browser_quest_app_menu($a,&$b) {
$b['app_menu'][] = '<div class="app-title"><a href="browser_quest">' . t('Browser Quest') . '</a></div>';
}


function browser_quest_module() {
return;
}


function browser_quest_content(&$a) {
$baseurl = $a->get_baseurl() . '/addon/browser_quest';
$o = '';

$a->page['htmlhead'] .= '<link rel="stylesheet" type="text/css" href="'.$a->get_baseurl().'/addon/browser_quest/browser_quest.css"/>';





  $o .= <<< EOT

<iframe src="http://browserquest.mozilla.org" width="1024" height="800"></iframe>



<p>Please note, this is a third party website, it just happens to be a very spiffy one.</a></p>

EOT;
return $o;
    
}
