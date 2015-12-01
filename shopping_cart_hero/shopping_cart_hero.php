<?php

/**
 * Name: Shopping Cart Hero
 * Description: If there's one thing Friendica can't do, it's help you perform stunts on a shopping trolley, right?
 * Wrong.
 * Version: 1.0
 * Author: Thomas Willingham<http://kakste.com/profile/bouldrake>
 * Status: Unsupported
 */


function shopping_cart_hero_install() {
    register_hook('app_menu', 'addon/shopping_cart_hero/shopping_cart_hero.php', 'shopping_cart_hero_app_menu');
}

function shopping_cart_hero_uninstall() {
    unregister_hook('app_menu', 'addon/shopping_cart_hero/shopping_cart_hero.php', 'shopping_cart_hero_app_menu');

}

function shopping_cart_hero_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="shopping_cart_hero">Shopping Cart Hero</a></div>';
}


function shopping_cart_hero_module() {}

function shopping_cart_hero_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/shopping_cart_hero';

$o .= <<< EOT
<embed src="http://rattyslav.com/games/ShoppingCartHero3.swf" quality="high" bgcolor="#000000" width="620" height="480" name="shopping_cart_hero" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />

EOT;

return $o;
}
