<?php

/**
 * Name: Whatsapp WebClient
 * Description: Chat via Whatsapp!
 * 
 * Author: M. Wegener
 */


function whatsapp_webclient_install() {
    register_hook('app_menu', 'addon/whatsapp_webclient/whatsapp_webclient.php', 'whatsapp_webclient_app_menu');
}

function whatsapp_webclient_uninstall() {
    unregister_hook('app_menu', 'addon/whatsapp_webclient/whatsapp_webclient.php', 'whatsapp_webclient_app_menu');

}

function whatsapp_webclient_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="whatsapp_webclient">Whatsapp WebClient</a></div>';
}


function whatsapp_webclient_module() {}

function whatsapp_webclient_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/whatsapp_webclient';
$host= gethostname();
$IP = gethostbyname($host);

$o .= <<< EOT
<object data=http://$IP:3888/ width="750" height="600"> <embed src=http://$IP:3888/ width="600" height="400"> </embed><br>
Your Error Message
webmaster [at] domain [dot] tdl. </object>

EOT;

return $o;
}
