<?php


/**
 * Name: Noindex
 * Description: Inserts a noindex metatag in every page to prevent search engines from indexing the site.    
 * Version: 1.0
 * Author: Thed Hawk <http://elektra.libra.uberspace.de/profile/thedhawk>
 * 
 */

function noindex_install() {
    register_hook('page_content_top', 'addon/noindex/noindex.php', 'noindex_fetch');
}


function noindex_uninstall() {
    unregister_hook('page_content_top', 'addon/noindex/noindex.php', 'noindex_fetch');
}


function noindex_fetch($a) {

    $a->page['htmlhead'] .= '<meta name="robots" content="noindex" />' . "\r\n";
}

