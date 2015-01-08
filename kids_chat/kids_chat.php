<?php
/**
* Name: Kids Chat
* Description: Embeds kidzworld chat
* Version: 1.0
* Author: Thomas Willingham
*/

function kids_chat_install() {
register_hook('app_menu', 'addon/kids_chat/kids_chat.php', 'kids_chat_app_menu');
}

function kids_chat_uninstall() {
unregister_hook('app_menu', 'addon/kids_chat/kids_chat.php', 'kids_chat_app_menu');

}

function kids_chat_app_menu($a,&$b) {
$b['app_menu'][] = '<div class="app-title"><a href="kids_chat">' . t('Kids Chat') . '</a></div>';
}


function kids_chat_module() {
return;
}


function kids_chat_content(&$a) {
$baseurl = $a->get_baseurl() . '/addon/kids_chat';
$o = '';

$a->page['htmlhead'] .= '<link rel="stylesheet" type="text/css" href="'.$a->get_baseurl().'/addon/kids_chat/kids_chat.css"/>';





  $o .= <<< EOT

<iframe src="http://www.kidzworld.com/chat" width="1024" height="800"></iframe>


EOT;
return $o;
    
}
