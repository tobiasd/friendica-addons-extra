<?php
/**
 * Name: Latest Tweets Widget
 * Description: shows a latest tweets widget in the sidebar and/or the profile
 * Version: 0.2
 * Author: Tobias Diekershoff <http://diekershoff.homeunix.net/friendica/profile/tobias>
 *
 * Copyright (c) 2013 Tobias Diekershoff
 * All rights reserved.
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */


function lasttweets_install() {
    register_hook('profile_advanced', 'addon/lasttweets/lasttweets.php', 'lasttweets_profile_advanced');
    register_hook('network_mod_init', 'addon/lasttweets/lasttweets.php', 'lasttweets_mod_init');
    register_hook('plugin_settings', 'addon/lasttweets/lasttweets.php', 'lasttweets_settings');
    register_hook('plugin_settings_post', 'addon/lasttweets/lasttweets.php', 'lasttweets_settings_post');
}

function lasttweets_uninstall() {
    unregister_hook('profile_advanced', 'addon/lasttweets/lasttweets.php', 'lasttweets_profile_advanced');
    unregister_hook('network_mod_init', 'addon/lasttweets/lasttweets.php', 'lasttweets_mod_init');
    unregister_hook('plugin_settings', 'addon/lasttweets/lasttweets.php', 'lasttweets_settings');
    unregister_hook('plugin_settings_post', 'addon/lasttweets/lasttweets.php', 'lasttweets_settings_post');
}
function lasttweets_settings (&$a, &$s) {
    if(! local_user())
            return;
    $tw_sb_search = get_pconfig(local_user(),'twitterwidget','sb_search');
    $tw_pr_search = get_pconfig(local_user(),'twitterwidget','pr_search');
    $tw_sb_icolor = get_pconfig(local_user(),'twitterwidget','sb_icolor');
    $tw_sb_ocolor = get_pconfig(local_user(),'twitterwidget','sb_ocolor');
    $tw_pr_icolor = get_pconfig(local_user(),'twitterwidget','pr_icolor');
    $tw_pr_ocolor = get_pconfig(local_user(),'twitterwidget','pr_ocolor');
    if (intval(get_pconfig(local_user(),'twitterwidget','sb_enable'))==1) { 
        $tw_sb_checked = ' checked="checked" '; 
    } else { 
        $tw_sb_checked = ''; 
    }
    if (intval(get_pconfig(local_user(),'twitterwidget','pr_enable'))==1) { 
        $tw_pr_checked = ' checked="checked" '; 
    } else { 
        $tw_pr_checked = ''; 
    }
    $s .= '<div class="settings-block">';
    $s .= '<h3>'.t('Twitter Widget Settings').'</h3>';
    $s .= '<p>'.t('You can include the widget in the sidebar of your Network strean (for your eyes only) and on the bottom of your profile page (for everybody). Both locations can be enabled separately with different search items and settings.').'</p>';

    $s .= '<h4>'.t('Sidebar Settings').'</h4>';
    $s .= '<div id="twitterwidget-wrapper">';
    $s .= '<label id="twitterwidget-sb-search-label" for="twitterwidget-sb-search">'.t('Search Item at Twitter').'</label>';
    $s .= '<input id="twitterwidget-sb-search" type="text" name="twitterwidget-sb-search" value="'.$tw_sb_search.'" />';
    $s .= '<div class="clear"></div>';

    $s .= '<label id="twitterwidget-sb-innercolor-label" for="twitterwidget-sb-innercolor">'.t('Inner Color (hexcode)').'</label>';
    $s .= '<input id="twitterwidget-sb-innercolor" type="text" name="twitterwidget-sb-innercolor" value="'.$tw_sb_icolor.'" />';
    $s .= '<div class="clear"></div>';

    $s .= '<label id="twitterwidget-sb-outercolor-label" for="twitterwidget-sb-outercolor">'.t('Outer Color (hexcode)').'</label>';
    $s .= '<input id="twitterwidget-sb-outercolor" type="text" name="twitterwidget-sb-outercolor" value="'.$tw_sb_ocolor.'" />';
    $s .= '<div class="clear"></div>';

    $s .= '<label id="twitterwidget-sb-enable-label" for="twitterwidget-sb-enable">' . t('Enable Twitter Widget (Sidebar)') . ' </label>';
    $s .= '<input id="twitterwidget-sb-enable" type="checkbox" name="twitterwidget-sb-enable" value="1"' . $tw_sb_checked . ' />';

    $s .= '<h4>'.t('Profile Settings').'</h4>';
    $s .= '<div id="twitterwidget-wrapper">';
    $s .= '<label id="twitterwidget-pr-search-label" for="twitterwidget-pr-search">'.t('Search Item at Twitter').'</label>';
    $s .= '<input id="twitterwidget-pr-search" type="text" name="twitterwidget-pr-search" value="'.$tw_pr_search.'" />';
    $s .= '<div class="clear"></div>';

    $s .= '<label id="twitterwidget-pr-innercolor-label" for="twitterwidget-pr-innercolor">'.t('Inner Color (hexcode)').'</label>';
    $s .= '<input id="twitterwidget-pr-innercolor" type="text" name="twitterwidget-pr-innercolor" value="'.$tw_pr_icolor.'" />';
    $s .= '<div class="clear"></div>';

    $s .= '<label id="twitterwidget-pr-outercolor-label" for="twitterwidget-pr-outercolor">'.t('Outer Color (hexcode)').'</label>';
    $s .= '<input id="twitterwidget-pr-outercolor" type="text" name="twitterwidget-pr-outercolor" value="'.$tw_pr_ocolor.'" />';
    $s .= '<div class="clear"></div>';

    $s .= '<label id="twitterwidget-pr-enable-label" for="twitterwidget-pr-enable">' . t('Enable Twitter Widget (Below Profile)') . ' </label>';
    $s .= '<input id="twitterwidget-pr-enable" type="checkbox" name="twitterwidget-pr-enable" value="1"' . $tw_pr_checked . ' />';
    $s .= '</div><div class="clear"></div>';
    
    $s .= '<div class="twitterwidget-submit-wrapper" ><input type="submit" name="twitterwidget-submit" class="twitterwidget-submit" value="' . t('Submit') . '" /></div></div>';
    $s .= '<div class="clear"></div>';
    return;
}
function lasttweets_settings_post ($a, $post) {
    if(! local_user())
            return;
    if (!x($_POST,'twitterwidget-submit')) return;
    set_pconfig(local_user(), 'twitterwidget','sb_icolor',$_POST['twitterwidget-sb-innercolor']);
    set_pconfig(local_user(), 'twitterwidget','sb_ocolor',$_POST['twitterwidget-sb-outercolor']);
    set_pconfig(local_user(), 'twitterwidget','sb_search',$_POST['twitterwidget-sb-search']);
    set_pconfig(local_user(), 'twitterwidget','pr_search',$_POST['twitterwidget-pr-search']);
    set_pconfig(local_user(), 'twitterwidget','pr_icolor',$_POST['twitterwidget-pr-innercolor']);
    set_pconfig(local_user(), 'twitterwidget','pr_ocolor',$_POST['twitterwidget-pr-outercolor']);
    set_pconfig(local_user(), 'twitterwidget','sb_enable',intval($_POST['twitterwidget-sb-enable']));
    set_pconfig(local_user(), 'twitterwidget','pr_enable',intval($_POST['twitterwidget-pr-enable']));
}
function lasttweets_mod_init($a, &$b) {
    if(! local_user())
            return;
    if (intval(get_pconfig(local_user(),'twitterwidget','sb_enable'))==1) {
        $tw_sb_search = get_pconfig(local_user(),'twitterwidget','sb_search');
        $tw_sb_icolor = get_pconfig(local_user(),'twitterwidget','sb_icolor');
        $tw_sb_ocolor = get_pconfig(local_user(),'twitterwidget','sb_ocolor');
        $a->page['htmlhead'] .= '<script type="text/javascript" src="'.$a->get_baseurl().'/addon/lasttweets/js/jquery.twitter.search.js"></script>'."\r\n";
        $a->page['htmlhead'] .= '<link rel="stylesheet"  type="text/css" href="' . $a->get_baseurl() . '/addon/lasttweets/style.css"' . ' media="all" />' . "\r\n";
        $twitter = '<script type="text/javascript">'."\r\n";
        $twitter .= '$(document).ready(function() {'."\r\n";
        $twitter .= "$('#lasttweets').twitterSearch({"."\r\n";
        $twitter .= "term: '".$tw_sb_search."', animOut: { opacity: 1 }, colorExterior: '".$tw_sb_ocolor."',  colorInterior: '".$tw_sb_icolor."', avatar: false, anchors: true, bird: false, pause: true, time: true, timeout: 2000 });"."\r\n";
        $twitter .= '})'."\r\n";
        $twitter .= '</script>'."\r\n";
        $twitter .= '<div id="lasttweets" class="side-tweets"></div>';
        $a->page['aside'] = $twitter . $a->page['aside'];
    }
}
function lasttweets_profile_advanced($a, &$b) {
    if (intval(get_pconfig($a->profile['id'],'twitterwidget','pr_enable'))==1) {
        $tw_pr_search = get_pconfig($a->profile['id'],'twitterwidget','pr_search');
        $tw_pr_icolor = get_pconfig($a->profile['id'],'twitterwidget','pr_icolor');
        $tw_pr_ocolor = get_pconfig($a->profile['id'],'twitterwidget','pr_ocolor');
        $a->page['htmlhead'] .= '<script type="text/javascript" src="'.$a->get_baseurl().'/addon/lasttweets/js/jquery.twitter.search.js"></script>'."\r\n";
        $a->page['htmlhead'] .= '<link rel="stylesheet"  type="text/css" href="' . $a->get_baseurl() . '/addon/lasttweets/style.css"' . ' media="all" />' . "\r\n";
        $b .= '<script type="text/javascript">'."\r\n";
        $b .= '$(document).ready(function() {'."\r\n";
        $b .= "$('#lasttweets').twitterSearch({"."\r\n";
        $b .= "term: '".$tw_pr_search."', animOut: { opacity: 1 }, colorExterior: '".$tw_pr_ocolor."',  colorInterior: '".$tw_pr_icolor."',  avatar: true, anchors: true, bird: true, pause: true, time: true, timeout: 2000 });"."\r\n";
        $b .= '})'."\r\n";
        $b .= '</script>'."\r\n";
        $b .= '<div id="lasttweets" class="profile-tweets"></div>';
    }
}
