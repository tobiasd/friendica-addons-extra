<?php
/**
* Name: Video Chat
* Description: What was that name of the social network that had video chat again?  Oh yeah - Friendica.
* Version: 1.0
* Author: Thomas Willingham
*/

function video_chat_install() {
register_hook('app_menu', 'addon/video_chat/video_chat.php', 'video_chat_app_menu');
}

function video_chat_uninstall() {
unregister_hook('app_menu', 'addon/video_chat/video_chat.php', 'video_chat_app_menu');

}

function video_chat_app_menu($a,&$b) {
$b['app_menu'][] = '<div class="app-title"><a href="video_chat">' . t('Video Chat') . '</a></div>';
}


function video_chat_module() {
return;
}


function video_chat_content(&$a) {
  if((! (local_user())) && (! (remote_user()))) {
	info( t("You must be authenticated to use this addon!  Either login, or visit this site via a magic link. "));

                return;}

$baseurl = $a->get_baseurl() . '/addon/video_chat';
$o = '';

$a->page['htmlhead'] .= '<link rel="stylesheet" type="text/css" href="'.$a->get_baseurl().'/addon/video_chat/zero-fix.css"/>';


  $o .= <<< EOT

<iframe src="$baseurl/index.html" width="1024" height="800"></iframe>


EOT;
return $o;
    
}
