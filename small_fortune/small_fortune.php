<?php
/**
 * Name: small_fortune
 * Description: Add a random fortune cookie at the bottom of every pages. [Remote hosted version for shared hosts]
 * Version: 1.0
 * Author: Mike Macgirvin <http://macgirvin.com/profile/mike>
 * Author: "Small" part added by Thomas Willingham <https://kakste.com/profile/beardyunixer>
 */


define ('small_fortune_SERVER', 'yamkote.com');

function small_fortune_install() {
	register_hook('page_end', 'addon/small_fortune/small_fortune.php', 'small_fortune_fetch');
	if(small_fortune_SERVER == 'hostname.com' && is_site_admin()) {
		notice('small_fortune plugin requires configuration. See README');
	}
}

function small_fortune_uninstall() {
	unregister_hook('page_end', 'addon/small_fortune/small_fortune.php', 'small_fortune_fetch');
}


function small_fortune_fetch(&$a,&$b) {

	$a->page['htmlhead'] .= '<link rel="stylesheet" type="text/css" href="' 
		. $a->get_baseurl() . '/addon/small_fortune/small_fortune.css' . '" media="all" />' . "\r\n";

	if(small_fortune_SERVER != 'hostname.com') {
		$s = fetch_url('http://' . small_fortune_SERVER . '/cookie.php?numlines=2&equal=1&rand=' . mt_rand());
		$b .= '<div class="small_fortune">' . $s . '</div>';
	}
}

