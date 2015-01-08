<?php


/**
 * Name: Fart
 * Description: Makes Friendica fart
 * Version: 1.0
 * Author: 
 * 
 */

function fart_install() {
    register_hook('page_content_top', 'addon/fart/fart.php', 'fart_fetch');
}


function fart_uninstall() {
    unregister_hook('page_content_top', 'addon/fart/fart.php', 'fart_fetch');
}


function fart_fetch($a) {

 $channel_display = get_pconfig($a->profile['profile_uid'],'system','channel_format');

$fart = get_pconfig($a->data['channel']['channel_id']),'geocities','fart'));

if (! $fart){return;}

$a->page['htmlhead'] .= <<< EOT
	
	
      <script src="addon/fart/fartscroll.js"></script>
        <script>
        $(document).ready(function() {
            fartscroll(100);
        });
        </script>
EOT;
	}
}