<?php
/**
 * Name: Etherpad-Lite App
 * Description: embed a etherpad-lite as app
 * Version: 1.2
 * Author: Tobias Diekershoff <http://diekershoff.homeunix.net/friendica/profile/tobias>
 *
 * Copyright (c) 2012 Tobias Diekershoff
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


function etherpadlite_install() {
    register_hook('app_menu', 'addon/etherpadlite/etherpadlite.php', 'etherpadlite_app_menu');
}

function etherpadlite_uninstall() {
    unregister_hook('app_menu', 'addon/etherpadlite/etherpadlite.php', 'etherpadlite_app_menu');
}

function etherpadlite_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="etherpadlite">' . t('Etherpad-Lite') . '</a></div>'; 
}

function etherpadlite_module() {
	return;
}

function etherpadlite_content(&$a) {
    if (! local_user()) {
        $o = t("Only local user can access the Etherpad-Lite app.");
        return $o;
    }
    $baseurl = get_config('etherpadlite','baseurl');
    $user    = $a->user['username'];

    $o = "<iframe src='".$baseurl."?userName=".$user."' width=650 height=450></iframe>";

  return $o;

}
function etherpadlite_plugin_admin (&$a, &$o) {
    $t = get_markup_template( "admin.tpl", "addon/etherpadlite/" );
    $o = replace_macros( $t, array(
            '$submit' => t('Submit'),
            '$baseurl' => array('baseurl', t('Etherpad-Lite Base URL'), get_config('etherpadlite','baseurl' ), t('Absolute path to your Etherpad-Lite installation. (with trailing slash)')),
    ));
}
function etherpadlite_plugin_admin_post (&$a) {
    $url = ((x($_POST, 'baseurl')) ? notags(trim($_POST['baseurl'])) : '');
    set_config('etherpadlite', 'baseurl', $url);
    info( t('Settings updated.'). EOL);
}
