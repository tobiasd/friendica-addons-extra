<?php
/**
 * Name: Facebook Group Connector
 * Version: 1.0
 * Author: Mike Macgirvin <http://macgirvin.com/profile/mike>
 * Author: Tobias Hößl <https://github.com/CatoTH/>
 * Author: Hans Ryding <http://hej.mykorrhiza.se/profile/hans>
 *
 */

/**
 * Installing the Friendica/Facebook connector
 *
 * Detailed instructions how to use this plugin can be found at
 * https://github.com/friendica/friendica/wiki/How-to:-Friendica%E2%80%99s-Facebook-connector
 *
 * Vidoes and embeds will not be posted if there is no other content. Links 
 * and images will be converted to a format suitable for the Facebook API and 
 * long posts truncated - with a link to view the full post. 
 *
 * Facebook contacts will not be able to view private photos, as they are not able to
 * authenticate to your site to establish identity. We will address this 
 * in a future release.
 */

define('FACEBOOK_DEFAULT_POLL_INTERVAL', 5); // given in minutes
define('DEFAULT_FACEBOOK_GROUP', 205478802843211); // hardcoded default facebook group

require_once('include/security.php');

function fbgroup_install() {
	register_hook('post_local',       'addon/fbgroup/fbgroup.php', 'fbpost_post_local');
	register_hook('notifier_normal',  'addon/fbgroup/fbgroup.php', 'fbpost_post_hook');
	register_hook('jot_networks',     'addon/fbgroup/fbgroup.php', 'fbpost_jot_nets');
	register_hook('connector_settings', 'addon/fbgroup/fbgroup.php', 'fbgroup_plugin_settings');
	register_hook('enotify',          'addon/fbgroup/fbgroup.php', 'fbpost_enotify');
	register_hook('queue_predeliver', 'addon/fbgroup/fbgroup.php', 'fbpost_queue_hook');
	register_hook('cron', 		  'addon/fbgroup/fbgroup.php', 'fbpost_cron');
}


function fbgroup_uninstall() {
	unregister_hook('post_local',       'addon/fbgroup/fbgroup.php', 'fbpost_post_local');
	unregister_hook('notifier_normal',  'addon/fbgroup/fbgroup.php', 'fbpost_post_hook');
	unregister_hook('jot_networks',     'addon/fbgroup/fbgroup.php', 'fbpost_jot_nets');
	unregister_hook('connector_settings', 'addon/fbgroup/fbgroup.php', 'fbgroup_plugin_settings');
	unregister_hook('enotify',          'addon/fbgroup/fbgroup.php', 'fbpost_enotify');
	unregister_hook('queue_predeliver', 'addon/fbgroup/fbgroup.php', 'fbpost_queue_hook');
	unregister_hook('cron', 	    'addon/fbgroup/fbgroup.php', 'fbpost_cron');
}


/* declare the fbgroup_module function so that /fbgroup url requests will land here */

function fbgroup_module() {}

// If a->argv[1] is a nickname, this is a callback from Facebook oauth requests.
// If $_REQUEST["realtime_cb"] is set, this is a callback from the Real-Time Updates API

/**
 * @param App $a
 */
function fbgroup_init(&$a) {

	if($a->argc != 2)
		return;

	$nick = $a->argv[1];

	if(strlen($nick))
		$r = q("SELECT `uid` FROM `user` WHERE `nickname` = '%s' LIMIT 1",
				dbesc($nick)
		);
	if(!(isset($r) && count($r)))
		return;

	$uid           = $r[0]['uid'];
	$auth_code     = (x($_GET, 'code') ? $_GET['code'] : '');
	$error         = (x($_GET, 'error_description') ? $_GET['error_description'] : '');


	if($error)
		logger('fbpost_init: Error: ' . $error);

	if($auth_code && $uid) {

		$appid = get_config('fbpost','appid');
		$appsecret = get_config('fbpost', 'appsecret');

		$x = fetch_url('https://graph.facebook.com/oauth/access_token?client_id='
			. $appid . '&client_secret=' . $appsecret . '&redirect_uri='
			. urlencode($a->get_baseurl() . '/fbgroup/' . $nick)
			. '&code=' . $auth_code);

		logger('fbpost_init: returned access token: ' . $x, LOGGER_DATA);

		if(strpos($x,'access_token=') !== false) {
			$token = str_replace('access_token=', '', $x);
 			if(strpos($token,'&') !== false)
				$token = substr($token,0,strpos($token,'&'));
			set_pconfig($uid,'fbpost','access_token',$token);
			set_pconfig($uid,'fbpost','post','1');
			fbpost_get_self($uid);
		}

	}

}


/**
 * @param int $uid
 */
function fbpost_get_self($uid) {
	$access_token = get_pconfig($uid,'fbpost','access_token');
	if(! $access_token)
		return;
	$s = fetch_url('https://graph.facebook.com/'.DEFAULT_FACEBOOK_GROUP.'/?access_token=' . $access_token);
	if($s) {
		$j = json_decode($s);
		set_pconfig($uid,'fbpost','self_id',(string) $j->id);
	}
}

// This is the POST method to the facebook settings page
// Content is posted to Facebook in the function fbpost_post_hook()

/**
 * @param App $a
 */
function fbgroup_post(&$a) {

	$uid = local_user();
	if($uid){


		$fbpost_limited = get_config('fbpost','crestrict');

		$value = ((x($_POST,'post_by_default')) ? intval($_POST['post_by_default']) : 0);
		set_pconfig($uid,'fbpost','post_by_default', $value);

		$value = ((x($_POST,'mirror_posts')) ? intval($_POST['mirror_posts']) : 0);
		set_pconfig($uid,'fbpost','mirror_posts', $value);

		$value = ((x($_POST,'suppress_view_on_friendica')) ? intval($_POST['suppress_view_on_friendica']) : 0);
		set_pconfig($uid,'fbpost','suppress_view_on_friendica', $value);

		$value = ((x($_POST,'post_to_page')) ? $_POST['post_to_page'] : "0-0");
		$values = explode("-", $value);
		set_pconfig($uid,'fbpost','post_to_page', $values[0]);
		set_pconfig($uid,'fbpost','page_access_token', $values[1]);

		info( t('Settings updated.') . EOL);
	}

	return;
}

// Facebook settings form

/**
 * @param App $a
 * @return string
 */
function fbgroup_content(&$a) {

	if(! local_user()) {
		notice( t('Permission denied.') . EOL);
		return '';
	}


	if(! service_class_allows(local_user(),'facebook_connect')) {
		notice( t('Permission denied.') . EOL);
		return upgrade_bool_message();
	}


	if($a->argc > 1 && $a->argv[1] === 'remove') {
		del_pconfig(local_user(),'fbpost','post');
		info( t('Facebook Group disabled') . EOL);
	}

	$o = '';
	
	$fbpost_installed = false;
	if (get_pconfig(local_user(),'fbpost','post')) {
		$access_token = get_pconfig(local_user(),'fbpost','access_token');
		if ($access_token) {
			$s = fetch_url('https://graph.facebook.com/'.DEFAULT_FACEBOOK_GROUP.'/feed?access_token=' . $access_token);
			if($s) {
				$j = json_decode($s);
				if (isset($j->data)) $fbpost_installed = true;
			}
		}
	}
	
	$appid = get_config('fbpost','appid');

	if(! $appid) {
		notice( t('Facebook API key is missing.') . EOL);
		return '';
	}

	$a->page['htmlhead'] .= '<link rel="stylesheet" type="text/css" href="'
		. $a->get_baseurl() . '/addon/fbgroup/fbgroup.css' . '" media="all" />' . "\r\n";

	$o .= '<h3>' . t('Facebook Group') . '</h3>';

	if(! $fbpost_installed) { 
		$o .= '<div id="fbpost-enable-wrapper">';

		$o .= '<a href="https://www.facebook.com/dialog/oauth?client_id=' . $appid . '&redirect_uri=' 
			. $a->get_baseurl() . '/fbgroup/' . $a->user['nickname'] . '&scope=read_stream,publish_stream,manage_pages,photo_upload,user_groups,offline_access">' . t('Install Facebook Group connector for this account.') . '</a>';
		$o .= '</div>';
	}

	if($fbpost_installed) {
		$o .= '<div id="fbpost-disable-wrapper">';

		$o .= '<a href="' . $a->get_baseurl() . '/fbgroup/remove' . '">' . t('Remove Facebook Group connector') . '</a></div>';

		$o .= '<div id="fbpost-enable-wrapper">';

		$o .= '<a href="https://www.facebook.com/dialog/oauth?client_id=' . $appid . '&redirect_uri=' 
			. $a->get_baseurl() . '/fbgroup/' . $a->user['nickname'] . '&scope=read_stream,publish_stream,manage_pages,photo_upload,user_groups,offline_access">' . t('Re-authenticate [This is necessary whenever your Facebook password is changed.]') . '</a>';
		$o .= '</div>';

		$o .= '<div id="fbpost-post-default-form">';
		$o .= '<form action="fbgroup" method="post" >';
		$post_by_default = get_pconfig(local_user(),'fbpost','post_by_default');
		$checked = (($post_by_default) ? ' checked="checked" ' : '');
		$o .= '<input type="checkbox" name="post_by_default" value="1"' . $checked . '/>' . ' ' . t('Post to Facebook group by default') . EOL;

		$suppress_view_on_friendica = get_pconfig(local_user(),'fbpost','suppress_view_on_friendica');
		$checked = (($suppress_view_on_friendica) ? ' checked="checked" ' : '');
		$o .= '<input type="checkbox" name="suppress_view_on_friendica" value="1"' . $checked . '/>' . ' ' . t('Suppress "View on friendica"') . EOL;

		$mirror_posts = get_pconfig(local_user(),'fbpost','mirror_posts');
		$checked = (($mirror_posts) ? ' checked="checked" ' : '');
		$o .= '<input type="checkbox" name="mirror_posts" value="1"' . $checked . '/>' . ' ' . t('Mirror wall posts from facebook to friendica.') . EOL;

		// List all pages
		$post_to_page = get_pconfig(local_user(),'fbpost','post_to_page');
		$page_access_token = get_pconfig(local_user(),'fbpost','page_access_token');
		$fbpost_token  = get_pconfig($a->user['uid'],'fbpost','access_token');
		$url = 'https://graph.facebook.com/me/accounts';
		$x = fetch_url($url."?access_token=".$fbpost_token);
		$accounts = json_decode($x);

		$o .= t("Post to page/group:")."<select name='post_to_page'>";
		if (intval($post_to_page) == 0)
			$o .= "<option value='0-0' selected>".t('None')."</option>";
		else
			$o .= "<option value='0-0'>".t('None')."</option>";

		foreach($accounts->data as $account) {
			if (is_array($account->perms))
				if ($post_to_page == $account->id)
					$o .= "<option value='".$account->id."-".$account->access_token."' selected>".$account->name."</option>";
				else
					$o .= "<option value='".$account->id."-".$account->access_token."'>".$account->name."</option>";
		}

		$url = 'https://graph.facebook.com/me/groups';
		$x = fetch_url($url."?access_token=".$fbpost_token);
		$groups = json_decode($x);

		foreach($groups->data as $group) {
			if ($post_to_page == $group->id)
				$o .= "<option value='".$group->id."-0' selected>".$group->name."</option>";
			else
				$o .= "<option value='".$group->id."-0'>".$group->name."</option>";
		}

		$o .= "</select>";

		$o .= '<p><input type="submit" name="submit" value="' . t('Submit') . '" /></form></div>';

	}

	return $o;
}

/**
 * @param App $a
 * @param null|object $b
 */
function fbgroup_plugin_settings(&$a,&$b) {

	$b .= '<div class="settings-block">';
	$b .= '<h3>' . t('Facebook Group') . '</h3>';
	$b .= '<a href="fbgroup">' . t('Facebook Group Settings') . '</a><br />';
	$b .= '</div>';

}


/**
 * @param App $a
 * @param null|object $o
 */
function fbgroup_plugin_admin(&$a, &$o){


	$o = '<input type="hidden" name="form_security_token" value="' . get_form_security_token("fbsave") . '">';
	
	$o .= '<h4>' . t('Facebook API Key') . '</h4>';
	
	$appid  = get_config('fbpost', 'appid'  );
	$appsecret = get_config('fbpost', 'appsecret' );
	
	$ret1 = q("SELECT `v` FROM `config` WHERE `cat` = 'fbpost' AND `k` = 'appid' LIMIT 1");
	$ret2 = q("SELECT `v` FROM `config` WHERE `cat` = 'fbpost' AND `k` = 'appsecret' LIMIT 1");
	if ((count($ret1) > 0 && $ret1[0]['v'] != $appid) || (count($ret2) > 0 && $ret2[0]['v'] != $appsecret)) $o .= t('Error: it appears that you have specified the App-ID and -Secret in your .htconfig.php file. As long as they are specified there, they cannot be set using this form.<br><br>');
	
	$o .= '<label for="fbpost_appid">' . t('App-ID / API-Key') . '</label><input id="fbpost_appid" name="appid" type="text" value="' . escape_tags($appid ? $appid : "") . '"><br style="clear: both;">';
	$o .= '<label for="fbpost_appsecret">' . t('Application secret') . '</label><input id="fbpost_appsecret" name="appsecret" type="text" value="' . escape_tags($appsecret ? $appsecret : "") . '"><br style="clear: both;">';

	$o .= '<input type="submit" name="fbpost_save_keys" value="' . t('Save') . '">';
	
}

/**
 * @param App $a
 */

function fbgroup_plugin_admin_post(&$a){
	
check_form_security_token_redirectOnErr('/admin/plugins/fbgroup','fbsave');
	
	if (x($_REQUEST,'fbpost_save_keys')) {
		set_config('fbpost', 'appid', $_REQUEST['appid']);
		set_config('fbpost', 'appsecret', $_REQUEST['appsecret']);

		info(t('The new values have been saved.'));
	}

}

/**
 * @param App $a
 * @param object $b
 * @return mixed
 */
function fbpost_jot_nets(&$a,&$b) {
	if(! local_user())
		return;

	$fbpost_post = get_pconfig(local_user(),'fbpost','post');
	if(intval($fbpost_post) == 1) {
		$fbpost_defpost = get_pconfig(local_user(),'fbpost','post_by_default');
		$selected = ((intval($fbpost_defpost) == 1) ? ' checked="checked" ' : '');
		$b .= '<div class="profile-jot-net"><input type="checkbox" name="fbpost_enable"' . $selected . ' value="1" /> ' 
			. t('Post to Facebook Group') . '</div>';
	}
}

function fbpost_ShareAttributes($match) {

        $attributes = $match[1];

        $author = "";
        preg_match("/author='(.*?)'/ism", $attributes, $matches);
        if ($matches[1] != "")
                $author = $matches[1];

        preg_match('/author="(.*?)"/ism', $attributes, $matches);
        if ($matches[1] != "")
                $author = $matches[1];

        $headline = '<div class="shared_header">';

        $headline .= sprintf(t('%s:'), $author);

        $headline .= "</div>";

	//$text = "<br />".$headline."</strong><blockquote>".$match[2]."</blockquote>";
	$text = "\n\t".$match[2].":\t";

        return($text);
}


/**
 * @param App $a
 * @param object $b
 * @return mixed
 */
function fbpost_post_hook(&$a,&$b) {


	if($b['deleted'] || ($b['created'] !== $b['edited']))
		return;

	// if post comes from facebook don't send it back
	if($b['app'] == "Facebook")
		return;

	/**
	 * Post to Facebook stream
	 */

	require_once('include/group.php');
	require_once('include/html2plain.php');

	logger('Facebook Group Post');

	$reply = false;
	$likes = false;

	$deny_arr = array();
	$allow_arr = array();

	$toplevel = (($b['id'] == $b['parent']) ? true : false);


	$linking = ((get_pconfig($b['uid'],'fbpost','no_linking')) ? 0 : 
1);

	if((! $toplevel) && ($linking)) {
		$r = q("SELECT * FROM `item` WHERE `id` = %d AND `uid` = %d LIMIT 1",
			intval($b['parent']),
			intval($b['uid'])
		);
		if(count($r) && substr($r[0]['uri'],0,4) === 'fb::')
			$reply = substr($r[0]['uri'],4);
		elseif(count($r) && substr($r[0]['extid'],0,4) === 'fb::')
			$reply = substr($r[0]['extid'],4);
		else
			return;

		$u = q("SELECT * FROM user where uid = %d limit 1",
			intval($b['uid'])
		);
		if(! count($u))
			return;

		// only accept comments from the item owner. Other contacts are unknown to FB.

		if(! link_compare($b['author-link'], $a->get_baseurl() . '/profile/' . $u[0]['nickname']))
			return;


		logger('facebook reply id=' . $reply);
	}

	if(strstr($b['postopts'],'fbpost') || ($b['private']) || ($reply)) {

		if($b['private'] && $reply === false) {
			$allow_people = expand_acl($b['allow_cid']);
			$allow_groups = expand_groups(expand_acl($b['allow_gid']));
			$deny_people  = expand_acl($b['deny_cid']);
			$deny_groups  = expand_groups(expand_acl($b['deny_gid']));

			$recipients = array_unique(array_merge($allow_people,$allow_groups));
			$deny = array_unique(array_merge($deny_people,$deny_groups));

			$allow_str = dbesc(implode(', ',$recipients));
			if($allow_str) {
				$r = q("SELECT `notify` FROM `contact` WHERE `id` IN ( $allow_str ) AND `network` = 'face'"); 
				if(count($r))
					foreach($r as $rr)
						$allow_arr[] = $rr['notify'];
			}

			$deny_str = dbesc(implode(', ',$deny));
			if($deny_str) {
				$r = q("SELECT `notify` FROM `contact` WHERE `id` IN ( $deny_str ) AND `network` = 'face'"); 
				if(count($r))
					foreach($r as $rr)
						$deny_arr[] = $rr['notify'];
			}

			if(count($deny_arr) && (! count($allow_arr))) {

				// One or more FB folks were denied access but nobody on FB was specifically allowed access.
				// This might cause the post to be open to public on Facebook, but only to selected members
				// on another network. Since this could potentially leak a post to somebody who was denied, 
				// we will skip posting it to Facebook with a slightly vague but relevant message that will 
				// hopefully lead somebody to this code comment for a better explanation of what went wrong.

				notice( t('Post to Facebook cancelled because of multi-network access permission conflict.') . EOL);
				return;
			}


			// if it's a private message but no Facebook members are allowed or denied, skip Facebook post

			if((! count($allow_arr)) && (! count($deny_arr)))
				return;
		}

		if($b['verb'] == ACTIVITY_LIKE)
			$likes = true;


		$appid  = get_config('fbpost', 'appid'  );
		$secret = get_config('fbpost', 'appsecret' );

		if($appid && $secret) {

			logger('fbpost: have appid+secret');

			$fbpost_token  = get_pconfig($b['uid'],'fbpost','access_token');


			// post to facebook if it's a public post and we've ticked the 'post to Facebook' box,
			// or it's a private message with facebook participants
			// or it's a reply or likes action to an existing facebook post

			if($fbpost_token && ($toplevel || $b['private'] || $reply)) {
				logger('fbpost: able to post');
				require_once('library/facebook.php');
				require_once('include/bbcode.php');

				$msg = $b['body'];

				logger('Facebook group: original msg=' . $msg, LOGGER_DATA);

				// make links readable before we strip the code

				// unless it's a dislike - just send the text as a comment

				// if($b['verb'] == ACTIVITY_DISLIKE)
				//	$msg = trim(strip_tags(bbcode($msg)));

				// Old code
				/*$search_str = $a->get_baseurl() . '/search';

				if(preg_match("/\[url=(.*?)\](.*?)\[\/url\]/is",$msg,$matches)) {

					// don't use hashtags for message link

					if(strpos($matches[2],$search_str) === false) {
						$link = $matches[1];
						if(substr($matches[2],0,5) != '[img]')
							$linkname = $matches[2];
					}
				}

				// strip tag links to avoid link clutter, this really should be 
				// configurable because we're losing information

				$msg = preg_replace("/\#\[url=(.*?)\](.*?)\[\/url\]/is",'#$2',$msg);

				// provide the link separately for normal links
				$msg = preg_replace("/\[url=(.*?)\](.*?)\[\/url\]/is",'$2 $1',$msg);

				if(preg_match("/\[img\](.*?)\[\/img\]/is",$msg,$matches))
					$image = $matches[1];

				$msg = preg_replace("/\[img\](.*?)\[\/img\]/is", t('Image: ') . '$1', $msg);

				if((strpos($link,z_root()) !== false) && (! $image))
					$image = $a->get_baseurl() . '/images/friendica-64.jpg';

				$msg = trim(strip_tags(bbcode($msg)));*/

				// New code

				// Looking for the first image
				$image = '';
				if(preg_match("/\[img\=([0-9]*)x([0-9]*)\](.*?)\[\/img\]/is",$b['body'],$matches))
					$image = $matches[3];

				if ($image == '')
					if(preg_match("/\[img\](.*?)\[\/img\]/is",$b['body'],$matches))
						$image = $matches[1];

				// When saved into the database the content is sent through htmlspecialchars
				// That means that we have to decode all image-urls
				$image = htmlspecialchars_decode($image);

				// Checking for a bookmark element
				$body = $b['body'];
				if (strpos($body, "[bookmark") !== false) {
					// splitting the text in two parts:
					// before and after the bookmark
					$pos = strpos($body, "[bookmark");
					$body1 = substr($body, 0, $pos);
					$body2 = substr($body, $pos);

					// Removing the bookmark and all quotes after the bookmark
					// they are mostly only the content after the bookmark.
					$body2 = preg_replace("/\[bookmark\=([^\]]*)\](.*?)\[\/bookmark\]/ism",'',$body2);
					$body2 = preg_replace("/\[quote\=([^\]]*)\](.*?)\[\/quote\]/ism",'',$body2);
					$body2 = preg_replace("/\[quote\](.*?)\[\/quote\]/ism",'',$body2);

					$body = $body1.$body2;
				}

				// Convert recycle signs
				$body = str_replace("\t", " ", $body);
				// recycle 1
				$recycle = html_entity_decode("&#x2672; ", ENT_QUOTES, 'UTF-8');
				$body = preg_replace( '/'.$recycle.'\[url\=(\w+.*?)\](\w+.*?)\[\/url\]/i', "\n\t$2:\t", $body);
				// recycle 2 (Test)
				$recycle = html_entity_decode("&#x25CC; ", ENT_QUOTES, 'UTF-8');
				$body = preg_replace( '/'.$recycle.'\[url\=(\w+.*?)\](\w+.*?)\[\/url\]/i', "\n\t$2:\t", $body);

				// share element
				$body = preg_replace_callback("/\[share(.*?)\]\s?(.*?)\s?\[\/share\]/ism","fbpost_ShareAttributes", $body);

				$bodyparts = explode("\t", $body);
				// Doesn't help with multiple repeats - the problem has to be solved later
				if (sizeof($bodyparts) == 3) {
					$html = bbcode($bodyparts[2], false, false);
					$test = trim(html2plain($html, 0, true));

					if (trim($bodyparts[0]) == "")
						$body = trim($bodyparts[2]);
					else if (trim($test) == "")
						$body = trim($bodyparts[0]);
					else
						$body = trim($bodyparts[0])."\n\n".trim($bodyparts[1])."[quote]".trim($bodyparts[2])."[/quote]";
				} else
					$body = str_replace("\t", "", $body);

				// At first convert the text to html
				$html = bbcode($body, false, false);

				// Then convert it to plain text
				$msg = trim($b['title']." \n\n".html2plain($html, 0, true));

				// Removing useless spaces
				if (substr($msg, -2) == "«")
					$msg = trim(substr($msg, 0, -2))."«";

				$msg = html_entity_decode($msg,ENT_QUOTES,'UTF-8');

				// Removing multiple newlines
				while (strpos($msg, "\n\n\n") !== false)
					$msg = str_replace("\n\n\n", "\n\n", $msg);

				// add any attachments as text urls
				$arr = explode(',',$b['attach']);

				if(count($arr)) {
					$msg .= "\n";
        				foreach($arr as $r) {
            					$matches = false;
						$cnt = preg_match('|\[attach\]href=\"(.*?)\" size=\"(.*?)\" type=\"(.*?)\" title=\"(.*?)\"\[\/attach\]|',$r,$matches);
						if($cnt) {
							$msg .= "\n".$matches[1];
						}
					}
				}

				$link = '';
				$linkname = '';
				// look for bookmark-bbcode and handle it with priority
				if(preg_match("/\[bookmark\=([^\]]*)\](.*?)\[\/bookmark\]/is",$b['body'],$matches)) {
					$link = $matches[1];
					$linkname = $matches[2];
				}

				// If there is no bookmark element then take the first link
				if ($link == '') {
					$links = collecturls($html);
					if (sizeof($links) > 0) {
						reset($links);
						$link = current($links);
					}
				}

				// Remove trailing and leading spaces
				$msg = trim($msg);


				// Fallback - if message is empty
				if(!strlen($msg))
					$msg = $linkname;

				if(!strlen($msg))
					$msg = $link;

				if(!strlen($msg))
					$msg = $image;

				// If there is nothing to post then exit
				if(!strlen($msg))
					return;

				logger('Facebook group: msg=' . $msg, LOGGER_DATA);

				$video = "";

				if($likes) {
					$postvars = array('access_token' => $fbpost_token);
				} else {
					// message, picture, link, name, caption, description, source, place, tags
					if(trim($link) != "")
						if (@exif_imagetype($link) != 0) {
							$image = $link;
							$link = "";
						}

					$postvars = array(
						'access_token' => $fbpost_token,
						'message' => $msg
					);
					if(trim($image) != "")
						$postvars['picture'] = $image;

					if(trim($link) != "") {
						$postvars['link'] = $link;

						if ((stristr($link,'youtube')) || (stristr($link,'youtu.be')) || (stristr($link,'vimeo'))) {
							$video = $link;
						}
					}
					if(trim($linkname) != "")
						$postvars['name'] = $linkname;
				}

				if(($b['private']) && ($toplevel)) {
					$postvars['privacy'] = '{"value": "CUSTOM", "friends": "SOME_FRIENDS"';
					if(count($allow_arr))
						$postvars['privacy'] .= ',"allow": "' . implode(',',$allow_arr) . '"';
					if(count($deny_arr))
						$postvars['privacy'] .= ',"deny": "' . implode(',',$deny_arr) . '"';
					$postvars['privacy'] .= '}';

				}

				$post_to_page = get_pconfig($b['uid'],'fbpost','post_to_page');
				$page_access_token = get_pconfig($b['uid'],'fbpost','page_access_token');
				if ((intval($post_to_page) != 0) and ($page_access_token != ""))
					$target = $post_to_page;
				else
					$target = DEFAULT_FACEBOOK_GROUP;

				if($reply) {
					$url = 'https://graph.facebook.com/' . $reply . '/' . (($likes) ? 'likes' : 'comments');
				} else if (($video != "") or (($image == "") and ($link != ""))) {
					// If it is a link to a video or a link without a preview picture then post it as a link
					if ($video != "")
						$link = $video;

					$postvars = array(
						'access_token' => $fbpost_token,
						'link' => $link,
					);
					if ($msg != $video)
						$postvars['message'] = $msg;

					$url = 'https://graph.facebook.com/'.$target.'/links';
				} else if (($link == "") and ($image != "")) {
					// If it is only an image without a page link then post this image as a photo
					$postvars = array(
						'access_token' => $fbpost_token,
						'url' => $image,
					);
					if ($msg != $image)
						$postvars['message'] = $msg;

					$url = 'https://graph.facebook.com/'.$target.'/photos';
				} else if (($link != "") or ($image != "") or ($b['title'] == '') or (strlen($msg) < 500)) {
					$url = 'https://graph.facebook.com/'.$target.'/feed';
					if (!get_pconfig($b['uid'],'fbpost','suppress_view_on_friendica') and $b['plink'])
						$postvars['actions'] = '{"name": "' . t('View on Friendica') . '", "link": "' .  $b['plink'] . '"}';
				} else {
					// if its only a message and a subject and the message is larger than 500 characters then post it as note
					$postvars = array(
						'access_token' => $fbpost_token,
						'message' => bbcode($b['body'], false, false),
						'subject' => $b['title'],
					);
					$url = 'https://graph.facebook.com/'.$target.'/notes';
				}

				// Post to page?
				if (!$reply and ($target != "me") and $page_access_token)
					$postvars['access_token'] = $page_access_token;

				logger('fbpost: post to ' . $url);
				logger('fbpost: postvars: ' . print_r($postvars,true));

				// "test_mode" prevents anything from actually being posted.
				// Otherwise, let's do it.

				if(! get_config('fbpost','test_mode')) {
					$x = post_url($url, $postvars);
					logger('Facebook group post returns: ' . $x, LOGGER_DEBUG);
					$retj = json_decode($x);
					if($retj->id) {
						q("UPDATE `item` SET `extid` = '%s' WHERE `id` = %d LIMIT 1",
							dbesc('fb::' . $retj->id),
							intval($b['id'])
						);
					}
					else {
						if(! $likes) {
							$s = serialize(array('url' => $url, 'item' => $b['id'], 'post' => $postvars));
							require_once('include/queue_fn.php');
							add_to_queue($a->contact,NETWORK_FACEBOOK,$s);
							notice( t('Facebook group post failed. Queued for retry.') . EOL);
						}

						if (isset($retj->error) && $retj->error->type == "OAuthException" && $retj->error->code == 190) {
							logger('Facebook session has expired due to changed password.', LOGGER_DEBUG);

							$last_notification = get_pconfig($b['uid'], 'fbpost', 'session_expired_mailsent');
							if (!$last_notification || $last_notification < (time() - FACEBOOK_SESSION_ERR_NOTIFICATION_INTERVAL)) {
								require_once('include/enotify.php');

								$r = q("SELECT * FROM `user` WHERE `uid` = %d LIMIT 1", intval($b['uid']) );
								notification(array(
									'uid' => $b['uid'],
									'type' => NOTIFY_SYSTEM,
									'system_type' => 'facebook_connection_invalid',
									'language'     => $r[0]['language'],
									'to_name'      => $r[0]['username'],
									'to_email'     => $r[0]['email'],
									'source_name'  => t('Administrator'),
									'source_link'  => $a->config["system"]["url"],
									'source_photo' => $a->config["system"]["url"] . '/images/person-80.jpg',
								));

								set_pconfig($b['uid'], 'fbpost', 'session_expired_mailsent', time());
							} else logger('Facebook: No notification, as the last one was sent on ' . $last_notification, LOGGER_DEBUG);
						}
					}
				}
			}
		}
	}
}

/**
 * @param App $app
 * @param object $data
 */
function fbpost_enotify(&$app, &$data) {
	if (x($data, 'params') && $data['params']['type'] == NOTIFY_SYSTEM && x($data['params'], 'system_type') && $data['params']['system_type'] == 'facebook_connection_invalid') {
		$data['itemlink'] = '/facebook';
		$data['epreamble'] = $data['preamble'] = t('Your Facebook connection became invalid. Please Re-authenticate.');
		$data['subject'] = t('Facebook connection became invalid');
		$data['body'] = sprintf( t("Hi %1\$s,\n\nThe connection between your accounts on %2\$s and Facebook became invalid. This usually happens after you change your Facebook-password. To enable the connection again, you have to %3\$sre-authenticate the Facebook-connector%4\$s."), $data['params']['to_name'], "[url=" . $app->config["system"]["url"] . "]" . $app->config["sitename"] . "[/url]", "[url=" . $app->config["system"]["url"] . "/facebook]", "[/url]");
	}
}

/**
 * @param App $a
 * @param object $b
 */
function fbpost_post_local(&$a,&$b) {

	// Figure out if Facebook posting is enabled for this post and file it in 'postopts'
	// where we will discover it during background delivery.

	// This can only be triggered by a local user posting to their own wall.

	if((local_user()) && (local_user() == $b['uid'])) {

		$fbpost_post   = intval(get_pconfig(local_user(),'fbpost','post'));
		$fbpost_enable = (($fbpost_post && x($_REQUEST,'fbpost_enable')) ? intval($_REQUEST['fbpost_enable']) : 0);

		// if API is used, default to the chosen settings
		// but allow a specific override

		if($_REQUEST['api_source'] && intval(get_pconfig(local_user(),'fbpost','post_by_default'))) {
			if(! x($_REQUEST,'fbpost_enable'))
				$fbpost_enable = 1;
		}

		if(! $fbpost_enable)
			return;

		if(strlen($b['postopts']))
			$b['postopts'] .= ',';
		$b['postopts'] .= 'fbpost';
	}
}


/**
 * @param App $a
 * @param object $b
 */
function fbpost_queue_hook(&$a,&$b) {

	$qi = q("SELECT * FROM `queue` WHERE `network` = '%s'",
		dbesc(NETWORK_FACEBOOK)
	);
	if(! count($qi))
		return;

	require_once('include/queue_fn.php');

	foreach($qi as $x) {
		if($x['network'] !== NETWORK_FACEBOOK)
			continue;

		logger('fbpost_queue: run');

		$r = q("SELECT `user`.* FROM `user` LEFT JOIN `contact` on `contact`.`uid` = `user`.`uid` 
			WHERE `contact`.`self` = 1 AND `contact`.`id` = %d LIMIT 1",
			intval($x['cid'])
		);
		if(! count($r))
			continue;

		$user = $r[0];

		$appid  = get_config('fbpost', 'appid'  );
		$secret = get_config('fbpost', 'appsecret' );

		if($appid && $secret) {
			$fbpost_post   = intval(get_pconfig($user['uid'],'fbpost','post'));
			$fbpost_token  = get_pconfig($user['uid'],'fbpost','access_token');

			if($fbpost_post && $fbpost_token) {
				logger('fbpost_queue: able to post');
				require_once('library/facebook.php');

				$z = unserialize($x['content']);
				$item = $z['item'];
				$j = post_url($z['url'],$z['post']);

				$retj = json_decode($j);
				if($retj->id) {
					q("UPDATE `item` SET `extid` = '%s' WHERE `id` = %d LIMIT 1",
						dbesc('fb::' . $retj->id),
						intval($item)
					);
					logger('fbpost_queue: success: ' . $j); 
					remove_queue_item($x['id']);
				}
				else {
					logger('fbpost_queue: failed: ' . $j);
					update_queue_time($x['id']);
				}
			}
		}
	}
}


/**
 * @return bool|string
 */
function fbpost_get_app_access_token() {

	$acc_token = get_config('fbpost','app_access_token');

	if ($acc_token !== false) return $acc_token;

	$appid = get_config('fbpost','appid');
	$appsecret = get_config('fbpost', 'appsecret');

	if ($appid === false || $appsecret === false) {
		logger('fbpost_get_app_access_token: appid and/or appsecret not set', LOGGER_DEBUG);
		return false;
	}
	logger('https://graph.facebook.com/oauth/access_token?client_id=' . $appid . '&client_secret=' . $appsecret . '&grant_type=client_credentials', LOGGER_DATA);
	$x = fetch_url('https://graph.facebook.com/oauth/access_token?client_id=' . $appid . '&client_secret=' . $appsecret . '&grant_type=client_credentials');

	if(strpos($x,'access_token=') !== false) {
		logger('fbpost_get_app_access_token: returned access token: ' . $x, LOGGER_DATA);

		$token = str_replace('access_token=', '', $x);
 		if(strpos($token,'&') !== false)
			$token = substr($token,0,strpos($token,'&'));

		if ($token == "") {
			logger('fbpost_get_app_access_token: empty token: ' . $x, LOGGER_DEBUG);
			return false;
		}
		set_config('fbpost','app_access_token',$token);
		return $token;
	} else {
		logger('fbpost_get_app_access_token: response did not contain an access_token: ' . $x, LOGGER_DATA);
		return false;
	}
}

function fbpost_cron($a,$b) {
	$last = get_config('fbpost','last_poll');

	$poll_interval = intval(get_config('fbpost','poll_interval'));
	if(! $poll_interval)
		$poll_interval = FACEBOOK_DEFAULT_POLL_INTERVAL;

//	if($last) {
//		$next = $last + ($poll_interval * 60);
//		if($next > time()) {
//			logger('fbpost: poll intervall not reached');
//			return;
//		}
//	}
	logger('fbpost: cron_start');

	$r = q("SELECT * FROM `pconfig` WHERE `cat` = 'fbpost' AND `k` = 'mirror_posts' AND `v` = '1' ORDER BY RAND() ");
	if(count($r)) {
		foreach($r as $rr) {
			logger('fbpost: fetching for user '.$rr['uid']);
			fbpost_fetchwall($a, $rr['uid']);
		}
	}

	logger('fbpost: cron_end');

	set_config('fbpost','last_poll', time());
}

function fbpost_fetchwall($a, $uid) {

$access_token = get_pconfig($uid,'fbpost','access_token');
$post_to_page = get_pconfig($uid,'fbpost','post_to_page');
$lastcreated = get_pconfig($uid,'fbpost','last_created');

if ((int)$post_to_page == 0)
$post_to_page = "108902546382";

$url = "https://graph.facebook.com/".$post_to_page."/feed?access_token=".$access_token;

$first_time = ($lastcreated == "");

//if ($lastcreated != "")
//$url .= "&since=".urlencode($lastcreated);


$r = q("SELECT `user`.* FROM `user` LEFT JOIN `contact` on `contact`.`uid` = `user`.`uid` 
	WHERE `contact`.`self` = 1 AND `user`.`uid` = %d LIMIT 1",
	intval($uid)
);


$user = $r;
$feed = fetch_url($url);
$data = json_decode($feed);
$sync_comments = 1;
$top_item = 0;
$wall=1;

    foreach($data->data as $entry) {
		logger('fbpost_consume: entry: ' . print_r($entry,true), LOGGER_DATA);
		$datarray = array();

		$r = q("SELECT * FROM `item` WHERE ( `uri` = '%s' OR `extid` = '%s') AND `uid` = %d LIMIT 1",
				dbesc('fb::' . $entry->id),
				dbesc('fb::' . $entry->id),
				intval($uid)
		);
		if(count($r)) {
			$orig_post = $r[0];
			$top_item = $r[0]['id'];
		}
		else {
			$orig_post = null;
		}

		if(! $orig_post) {
			$datarray['gravity'] = 0;
			$datarray['uid'] = $uid;
			$datarray['wall'] = (($wall) ? 1 : 0);
			$datarray['uri'] = $datarray['parent-uri'] = 'fb::' . $entry->id;
			$from = $entry->from;
			if($from->id == $self_id)
				$datarray['contact-id'] = $self[0]['id'];
			else {
				// Looking if user is known - if not he is added
				$access_token = get_pconfig($uid, 'fbpost', 'access_token');
				fb_get_friends_sync_new($uid, $access_token, array($from));

				$r = q("SELECT * FROM `contact` WHERE `notify` = '%s' AND `uid` = %d AND `blocked` = 0 AND `readonly` = 0 LIMIT 1",
					dbesc($from->id),
					intval($uid)
				);
				if(count($r))
					$datarray['contact-id'] = $r[0]['id'];
			}

			// don't store post if we don't have a contact
			if(! x($datarray,'contact-id')) {
				logger('facebook: no contact '.$from->name.' '.$from->id.'. post ignored');
				continue;
			}

			$datarray['verb'] = ACTIVITY_POST;
			if($wall) {
				$datarray['owner-name'] = $self[0]['name'];
				$datarray['owner-link'] = $self[0]['url'];
				$datarray['owner-avatar'] = $self[0]['thumb'];
			}
			if(isset($entry->application) && isset($entry->application->name) && strlen($entry->application->name))
				$datarray['app'] = strip_tags($entry->application->name);
			else
				$datarray['app'] = 'facebook';

			$found_blocked = false;

			if(count($blocked_apps_arr)) {
				foreach($blocked_apps_arr as $bad_appl) {
					if(strlen(trim($bad_appl)) && (stristr($datarray['app'],trim($bad_appl)))) {
						$found_blocked = true;
					}
				}
			}
				
			if($found_blocked) {
				logger('facebook: blocking application: ' . $datarray['app']);
				continue;
			}

			$datarray['author-name'] = $from->name;
			$datarray['author-link'] = 'http://facebook.com/profile.php?id=' . $from->id;
			$datarray['author-avatar'] = 'https://graph.facebook.com/' . $from->id . '/picture';
			$datarray['plink'] = $datarray['author-link'] . '&v=wall&story_fbid=' . substr($entry->id,strpos($entry->id,'_') + 1);

			logger('facebook: post '.$entry->id.' from '.$from->name);

			$datarray['body'] = (isset($entry->message) ? escape_tags($entry->message) : '');

			if(isset($entry->name) and isset($entry->link))
				$datarray['body'] .= "\n\n[bookmark=".$entry->link."]".$entry->name."[/bookmark]";
			elseif (isset($entry->name))
				$datarray['body'] .= "\n\n[b]" . $entry->name."[/b]";

			if(isset($entry->caption)) {
				if(!isset($entry->name) and isset($entry->link))
					$datarray['body'] .= "\n\n[bookmark=".$entry->link."]".$entry->caption."[/bookmark]";
				else
					$datarray['body'] .= "[i]" . $entry->caption."[/i]\n";
			}

			if(!isset($entry->caption) and !isset($entry->name)) {
				if (isset($entry->link))
					$datarray['body'] .= "\n[url]".$entry->link."[/url]\n";
				else
					$datarray['body'] .= "\n";
			}

			$quote = "";
			if(isset($entry->description))
				$quote = $entry->description;

			if (isset($entry->properties))
				foreach ($entry->properties as $property)
					$quote .= "\n".$property->name.": [url=".$property->href."]".$property->text."[/url]";

			if ($quote)
				$datarray['body'] .= "\n[quote]".$quote."[/quote]";

			// Only import the picture when the message is no video
			// oembed display a picture of the video as well 
			if ($entry->type != "video") {
				if(isset($entry->picture) && isset($entry->link)) {
					$datarray['body'] .= "\n" . '[url=' . $entry->link . '][img]'.$entry->picture.'[/img][/url]';
				}
				else {
					if(isset($entry->picture))
						$datarray['body'] .= "\n" . '[img]' . $entry->picture . '[/img]';
					// if just a link, it may be a wall photo - check
					if(isset($entry->link))
						$datarray['body'] .= fbpost_get_photo($uid,$entry->link);
				}
			}

			if (($datarray['app'] == "Events") and isset($entry->actions))
				foreach ($entry->actions as $action)
					if ($action->name == "View")
						$datarray['body'] .= " [url=".$action->link."]".$entry->story."[/url]";

			// Just as a test - to see if these are the missing entries
			//if(trim($datarray['body']) == '')
			//	$datarray['body'] = $entry->story;

			// Adding the "story" text to see if there are useful data in it (testing)
			//if (($datarray['app'] != "Events") and $entry->story)
			//	$datarray['body'] .= "\n".$entry->story;

			if(trim($datarray['body']) == '') {
				logger('facebook: empty body '.$entry->id.' '.print_r($entry, true));
				continue;
			}

			$datarray['body'] .= "\n";

			if (isset($entry->icon))
				$datarray['body'] .= "[img]".$entry->icon."[/img] &nbsp; ";

			if (isset($entry->actions))
				foreach ($entry->actions as $action)
					if (($action->name != "Comment") and ($action->name != "Like"))
						$datarray['body'] .= "[url=".$action->link."]".$action->name."[/url] &nbsp; ";

			$datarray['body'] = trim($datarray['body']);

			//if(($datarray['body'] != '') and ($uid == 1))
			//	$datarray['body'] .= "[noparse]".print_r($entry, true)."[/noparse]";

            if (isset($entry->place)) {
			    if ($entry->place->name or $entry->place->location->street or
				    $entry->place->location->city or $entry->place->location->Denmark) {
				    $datarray['coord'] = '';
				    if ($entry->place->name)
					    $datarray['coord'] .= $entry->place->name;
				    if ($entry->place->location->street)
					    $datarray['coord'] .= $entry->place->location->street;
				    if ($entry->place->location->city)
					    $datarray['coord'] .= " ".$entry->place->location->city;
				    if ($entry->place->location->country)
					    $datarray['coord'] .= " ".$entry->place->location->country;
			    } else if ($entry->place->location->latitude and $entry->place->location->longitude)
				    $datarray['coord'] = substr($entry->place->location->latitude, 0, 8)
							.' '.substr($entry->place->location->longitude, 0, 8);
            }
			$datarray['created'] = datetime_convert('UTC','UTC',$entry->created_time);
			$datarray['edited'] = datetime_convert('UTC','UTC',$entry->updated_time);

			$top_item = item_store($datarray);
			$r = q("SELECT * FROM `item` WHERE `id` = %d AND `uid` = %d LIMIT 1",
				intval($top_item),
				intval($uid)
			);
			if(count($r)) {
				$orig_post = $r[0];
				logger('fb: new top level item posted');
			}
		}

		/**  @var array $orig_post */

        $likers_num = (isset($entry->likes) && isset($entry->likes->count) ? IntVal($entry->likes->count) : 0 );
		if(isset($entry->likes) && isset($entry->likes->data))
			$likers = $entry->likes->data;
		else
			$likers = null;

        $comments_num = (isset($entry->comments) && isset($entry->comments->count) ? IntVal($entry->comments->count) : 0 );
		if(isset($entry->comments) && isset($entry->comments->data))
			$comments = $entry->comments->data;
		else
			$comments = null;

        $needs_sync = false;

        if(is_array($likers)) {
			foreach($likers as $likes) fbpost_consume_like($a, $user, $self, $self_id, $wall, $orig_post, $likes);
            if ($sync_comments) {
                $r = q("SELECT COUNT(*) likes FROM `item` WHERE `parent-uri` = '%s' AND `uid` = %d AND `verb` = '%s' AND `parent-uri` != `uri`",
                    dbesc($orig_post['uri']),
                    intval($uid),
                    dbesc(ACTIVITY_LIKE)
                );
                if ($r[0]['likes'] < $likers_num) {
                    logger('fbpost_consume_stream: missing likes found for ' . $orig_post['uri'] . ' (we have ' . $r[0]['likes'] . ' of ' . $likers_num . '). Synchronizing...', LOGGER_DEBUG);
                    $needs_sync = true;
                }
            }
		}

		if(is_array($comments)) {
			foreach($comments as $cmnt) fbpost_consume_comment($a, $user, $self, $self_id, $wall, $orig_post, $cmnt);
			if ($sync_comments) {
			    $r = q("SELECT COUNT(*) comments FROM `item` WHERE `parent-uri` = '%s' AND `uid` = %d AND `verb` = '%s' AND `parent-uri` != `uri`",
                    dbesc($orig_post['uri']),
                    intval($uid),
                    ACTIVITY_POST
                );
			    if ($r[0]['comments'] < $comments_num) {
                    logger('fbpost_consume_stream: missing comments found for ' . $orig_post['uri'] . ' (we have ' . $r[0]['comments'] . ' of ' . $comments_num . '). Synchronizing...', LOGGER_DEBUG);
                    $needs_sync = true;
                }
			}
		}

		if ($needs_sync) fbpost_consume_status($a, $user, $entry, $self, $self_id, $wall, $orig_post);
	}

set_pconfig($uid,'fbpost','last_created', $lastcreated);
}

/**
 * @param App $a
 * @param array $user
 * @param array $self
 * @param string $fb_id
 * @param bool $wall
 * @param array $orig_post
 * @param object $cmnt
 */
function fbpost_consume_comment(&$a, &$user, &$self, $fb_id, $wall, &$orig_post, &$cmnt) {

    if(! $orig_post)
        return;

    $top_item = $orig_post['id'];
    $uid = IntVal($user[0]['uid']);

    $r = q("SELECT * FROM `item` WHERE `uid` = %d AND ( `uri` = '%s' OR `extid` = '%s' ) LIMIT 1",
        intval($uid),
        dbesc('fb::' . $cmnt->id),
        dbesc('fb::' . $cmnt->id)
    );
    if(count($r))
        return;

    $cmntdata = array();
    $cmntdata['parent'] = $top_item;
    $cmntdata['verb'] = ACTIVITY_POST;
    $cmntdata['gravity'] = 6;
    $cmntdata['uid'] = $uid;
    $cmntdata['wall'] = (($wall) ? 1 : 0);
    $cmntdata['uri'] = 'fb::' . $cmnt->id;
    $cmntdata['parent-uri'] = $orig_post['uri'];
    if($cmnt->from->id == $fb_id) {
        $cmntdata['contact-id'] = $self[0]['id'];
    }
    else {
        $r = q("SELECT * FROM `contact` WHERE `notify` = '%s' AND `uid` = %d LIMIT 1",
            dbesc($cmnt->from->id),
            intval($uid)
        );
        if(count($r)) {
            $cmntdata['contact-id'] = $r[0]['id'];
            if($r[0]['blocked'] || $r[0]['readonly'])
                return;
        }
    }
    if(! x($cmntdata,'contact-id'))
        $cmntdata['contact-id'] = $orig_post['contact-id'];

    $cmntdata['app'] = 'facebook';
    $cmntdata['created'] = datetime_convert('UTC','UTC',$cmnt->created_time);
    $cmntdata['edited']  = datetime_convert('UTC','UTC',$cmnt->created_time);
    $cmntdata['verb'] = ACTIVITY_POST;
    $cmntdata['author-name'] = $cmnt->from->name;
    $cmntdata['author-link'] = 'http://facebook.com/profile.php?id=' . $cmnt->from->id;
    $cmntdata['author-avatar'] = 'https://graph.facebook.com/' . $cmnt->from->id . '/picture';
    $cmntdata['body'] = $cmnt->message;
    $item = item_store($cmntdata);

    $myconv = q("SELECT `author-link`, `author-avatar`, `parent` FROM `item` WHERE `parent-uri` = '%s' AND `uid` = %d AND `parent` != 0 AND `deleted` = 0",
        dbesc($orig_post['uri']),
        intval($uid)
    );

    if(count($myconv)) {
        $importer_url = $a->get_baseurl() . '/profile/' . $user[0]['nickname'];

        foreach($myconv as $conv) {

            // now if we find a match, it means we're in this conversation

            if(! link_compare($conv['author-link'],$importer_url))
                continue;

            require_once('include/enotify.php');

            $conv_parent = $conv['parent'];

            notification(array(
                'type'         => NOTIFY_COMMENT,
                'notify_flags' => $user[0]['notify-flags'],
                'language'     => $user[0]['language'],
                'to_name'      => $user[0]['username'],
                'to_email'     => $user[0]['email'],
                'uid'          => $user[0]['uid'],
                'item'         => $cmntdata,
                'link'		   => $a->get_baseurl() . '/display/' . $user[0]['nickname'] . '/' . $item,
                'source_name'  => $cmntdata['author-name'],
                'source_link'  => $cmntdata['author-link'],
                'source_photo' => $cmntdata['author-avatar'],
                'verb'         => ACTIVITY_POST,
                'otype'        => 'item',
                'parent'       => $conv_parent,
            ));

            // only send one notification
            break;
        }
    }
}

/**
 * @param App $a
 * @param array $user
 * @param array $self
 * @param string $fb_id
 * @param bool $wall
 * @param array $orig_post
 * @param object $likes
 */
function fbpost_consume_like(&$a, &$user, &$self, $fb_id, $wall, &$orig_post, &$likes) {

    $top_item = $orig_post['id'];
    $uid = IntVal($user[0]['uid']);

    if(! $orig_post)
        return;

    // If we posted the like locally, it will be found with our url, not the FB url.

    $second_url = (($likes->id == $fb_id) ? $self[0]['url'] : 'http://facebook.com/profile.php?id=' . $likes->id);

    $r = q("SELECT * FROM `item` WHERE `parent-uri` = '%s' AND `uid` = %d AND `verb` = '%s'
    	AND ( `author-link` = '%s' OR `author-link` = '%s' ) LIMIT 1",
        dbesc($orig_post['uri']),
        intval($uid),
        dbesc(ACTIVITY_LIKE),
        dbesc('http://facebook.com/profile.php?id=' . $likes->id),
        dbesc($second_url)
    );

    if(count($r))
        return;

    $likedata = array();
    $likedata['parent'] = $top_item;
    $likedata['verb'] = ACTIVITY_LIKE;
    $likedata['gravity'] = 3;
    $likedata['uid'] = $uid;
    $likedata['wall'] = (($wall) ? 1 : 0);
    $likedata['uri'] = item_new_uri($a->get_baseurl(), $uid);
    $likedata['parent-uri'] = $orig_post['uri'];
    if($likes->id == $fb_id)
        $likedata['contact-id'] = $self[0]['id'];
    else {
        $r = q("SELECT * FROM `contact` WHERE `notify` = '%s' AND `uid` = %d AND `blocked` = 0 AND `readonly` = 0 LIMIT 1",
            dbesc($likes->id),
            intval($uid)
        );
        if(count($r))
            $likedata['contact-id'] = $r[0]['id'];
    }
    if(! x($likedata,'contact-id'))
        $likedata['contact-id'] = $orig_post['contact-id'];

    $likedata['app'] = 'facebook';
    $likedata['verb'] = ACTIVITY_LIKE;
    $likedata['author-name'] = $likes->name;
    $likedata['author-link'] = 'http://facebook.com/profile.php?id=' . $likes->id;
    $likedata['author-avatar'] = 'https://graph.facebook.com/' . $likes->id . '/picture';

    $author  = '[url=' . $likedata['author-link'] . ']' . $likedata['author-name'] . '[/url]';
    $objauthor =  '[url=' . $orig_post['author-link'] . ']' . $orig_post['author-name'] . '[/url]';
    $post_type = t('status');
    $plink = '[url=' . $orig_post['plink'] . ']' . $post_type . '[/url]';
    $likedata['object-type'] = ACTIVITY_OBJ_NOTE;

    $likedata['body'] = sprintf( t('%1$feed likes %2$feed\'s %3$feed'), $author, $objauthor, $plink);
    $likedata['object'] = '<object><type>' . ACTIVITY_OBJ_NOTE . '</type><local>1</local>' . '<id>' . $orig_post['uri'] . '</id><link>' . xmlify('<link rel="alternate" type="text/html" href="' . xmlify($orig_post['plink']) . '" />') . '</link><title>' . $orig_post['title'] . '</title><content>' . $orig_post['body'] . '</content></object>';

    item_store($likedata);
}

/**
 * @param App $a
 * @param array $user
 * @param object $entry
 * @param array $self
 * @param string $fb_id
 * @param bool $wall
 * @param array $orig_post
 */
function fbpost_consume_status(&$a, &$user, &$entry, &$self, $fb_id, $wall, &$orig_post) {
    $uid = IntVal($user[0]['uid']);
    $access_token = get_pconfig($uid, 'fbpost', 'access_token');

    $feed = fetch_url('https://graph.facebook.com/' . $entry->id . '?access_token=' . $access_token);
    if($feed) {
        $data = json_decode($feed);
        if (isset($data->comments) && isset($data->comments->data))
            foreach ($data->comments->data as $cmnt)
                fbpost_consume_comment($a, $user, $self, $fb_id, $wall, $orig_post, $cmnt);

        if (isset($data->likes) && isset($data->likes->data) && isset($data->likes->count)) {
            if (count($data->likes->data) == $data->likes->count) {
                foreach ($data->likes->data as $likers) fbpost_consume_like($a, $user, $self, $fb_id, $wall, $orig_post, $likers);
            } else {
                $t = fetch_url('https://graph.facebook.com/' . $entry->id . '/likes?access_token=' . $access_token);
                if ($t) {
                    $k = json_decode($t);
                    if (isset($k->data))
                        foreach ($k->data as $likers)
                            fbpost_consume_like($a, $user, $self, $fb_id, $wall, $orig_post, $likers);
                }
            }
        }
    }
}


function fbpost_get_photo($uid,$link) {
	$access_token = get_pconfig($uid,'fbpost','access_token');
	if(! $access_token || (! stristr($link,'facebook.com/photo.php')))
		return "";

	$ret = preg_match('/fbid=([0-9]*)/',$link,$match);
	if($ret)
		$photo_id = $match[1];
	else
		return "";

	$x = fetch_url('https://graph.facebook.com/'.$photo_id.'?access_token='.$access_token);
	$j = json_decode($x);
	if($j->picture)
		return "\n\n".'[url='.$link.'][img]'.fpost_cleanpicture($j->picture).'[/img][/url]';

	return "";
}

function fpost_cleanpicture($image) {

	if (strpos($image, ".fbcdn.net/") and (substr($image, -6) == "_s.jpg"))
		$image = substr($image, 0, -6)."_n.jpg";

	$queryvar = fbpost_parse_query($image);
	if ($queryvar['url'] != "")
		$image = urldecode($queryvar['url']);

	return $image;
}

function fbpost_parse_query($var) {
	/**
	 *  Use this function to parse out the query array element from
	 *  the output of parse_url().
	*/
	$var  = parse_url($var, PHP_URL_QUERY);
	$var  = html_entity_decode($var);
	$var  = explode('&', $var);
	$arr  = array();

	foreach($var as $val) {
		$x          = explode('=', $val);
		$arr[$x[0]] = $x[1];
	}

	unset($val, $x, $var);
	return $arr;
}
