<?php

/**
 * Super Zombie Shooter.
 *
 * Name: Super Zombie shooter
 * Description: Zombie Shooting game
 * Version: 1.0
 * Author: Thomas Willingham<http://kakste.com/profile/bouldrake>
 * Status: Unsupported
 */


function super_zombie_shooter_install() {
    register_hook('app_menu', 'addon/super_zombie_shooter/super_zombie_shooter.php', 'super_zombie_shooter_app_menu');
}

function super_zombie_shooter_uninstall() {
    unregister_hook('app_menu', 'addon/super_zombie_shooter/super_zombie_shooter.php', 'super_zombie_shooter_app_menu');

}

function super_zombie_shooter_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="super_zombie_shooter">Super Zombie Shooter</a></div>';
}


function super_zombie_shooter_module() {}

function super_zombie_shooter_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/super_zombie_shooter';

$o .= <<< EOT
<embed src="http://arcade.kakste.com/wp-content/games/super-zombie-shooter_v507291.swf?affiliate_id=7626254991a0161f" quality="high" bgcolor="#000000" width="620" height="480" name="super_zombie_shooter" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />

EOT;

return $o;
}
