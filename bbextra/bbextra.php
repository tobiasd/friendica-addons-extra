<?php


/**
 * Name: BBExtra
 * Description: Extra - mostly annoying - BBCode
 * Version: 1.0
 * Author: Thomas Willingham <https://beardyunixer.com/channel/beardyunixer>
 * 
 */

function bbextra_install() {
    register_hook('bbcode', 'addon/bbextra/bbextra.php', 'bbextra_bbcode');
}


function bbextra_uninstall() {
    unregister_hook('bbcode', 'addon/bbextra/bbextra.php', 'bbextra_bbcode');
}

function bbextra_bbcode(&$a, &$Text) {
	// Check for blink tags
	$Text = preg_replace("(\[blink\](.*?)\[\/blink\])ism","<blink>$1</blink>",$Text);
	// Check for blink tags - this doesn't work in most browsers, so lets work out how to Jquery it later
	$Text = preg_replace("(\[marquee\](.*?)\[\/marquee\])ism","<marquee>$1</marquee>",$Text);
	// Check for headers
	$Text = preg_replace("(\[header\](.*?)\[\/header\])ism","<h1>$1</h1>",$Text);
	// Check for massive tags
	$Text = preg_replace("(\[massive\](.*?)\[\/massive\])ism","<font size=30>$1</font>",$Text);
	// Check for massive tags
	$Text = preg_replace("(\[huge\](.*?)\[\/huge\])ism","<font size=20>$1</font>",$Text);
	// Check for midimg tags
	$Text = preg_replace("/\[midimg\](.*?)\[\/midimg\]/ism", '<center><img class="zrl" src="$1" alt="' . t('Image/photo') . '" /></center>', $Text);
	$Text = preg_replace("/\[floatimg\](.*?)\[\/floatimg\]/ism", '<img style="float:left" class="zrl" src="$1" alt="' . t('Image/photo') . '" />', $Text);
	$Text = preg_replace("/\[leftimg\](.*?)\[\/leftimg\]/ism", '<img style="align:left" class="zrl" src="$1" alt="' . t('Image/photo') . '" />', $Text);
	$Text = preg_replace("/\[rightimg\](.*?)\[\/rightimg\]/ism", '<img style="align:right" class="zrl" src="$1" alt="' . t('Image/photo') . '" />', $Text);
}