<?php
/**
* Name: Reddit Reader
* Description: it's just a reddit widget
* Version: 1.0
* Author: Zen @TokTan.Org
*/

function reddit_install() {
register_hook('app_menu', 'addon/reddit/reddit.php', 'reddit_app_menu');
}

function reddit_uninstall() {
unregister_hook('app_menu', 'addon/reddit/reddit.php', 'reddit_app_menu');

}

function reddit_app_menu($a,&$b) {
$b['app_menu'][] = '<div class="app-title"><a href="reddit">' . t('Reddit Reader') . '</a></div>';
}


function reddit_module() {
return;
}


function reddit_content(&$a) {
$baseurl = $a->get_baseurl() . '/addon/reddit';
$o = '';

$a->page['htmlhead'] .= '<link rel="stylesheet" type="text/css" href="'.$a->get_baseurl().'/addon/reddit/reddit.css"/>';


$baseurl = $a->get_baseurl();


  $o .= <<< EOT

Top this week:<br>
<script src="http://www.reddit.com/hot/.embed?limit=10&t=week" type="text/javascript"></script><br>
New today:<br>
<script src="http://www.reddit.com/new/.embed?limit=20&t=day&sort=new" type="text/javascript"></script>




EOT;
return $o;
    
}