<?php
/**
 * Name: Cthulhu Mythos Locations
 * Description: Set a random location from the Cthulhu Mythos when posting.
 * Version: 1.0
 * Author: Mike Macgirvin <http://macgirvin.com/profile/mike>
 * Author: Thomas Willingham <https://kakste.com/profile/beardyunixer>
 */


function cthulhu_mythos_locations_install() {

	/**
	 * 
	 * Our demo plugin will attach in three places.
	 * The first is just prior to storing a local post.
	 *
	 */

	register_hook('post_local', 'addon/cthulhu_mythos_locations/cthulhu_mythos_locations.php', 'cthulhu_mythos_locations_post_hook');

	/**
	 *
	 * Then we'll attach into the plugin settings page, and also the 
	 * settings post hook so that we can create and update
	 * user preferences.
	 *
	 */

	register_hook('plugin_settings', 'addon/cthulhu_mythos_locations/cthulhu_mythos_locations.php', 'cthulhu_mythos_locations_settings');
	register_hook('plugin_settings_post', 'addon/cthulhu_mythos_locations/cthulhu_mythos_locations.php', 'cthulhu_mythos_locations_settings_post');

	logger("installed cthulhu_mythos_locations");
}


function cthulhu_mythos_locations_uninstall() {

	/**
	 *
	 * uninstall unregisters any hooks created with register_hook
	 * during install. It may also delete configuration settings
	 * and any other cleanup.
	 *
	 */

	unregister_hook('post_local',    'addon/cthulhu_mythos_locations/cthulhu_mythos_locations.php', 'cthulhu_mythos_locations_post_hook');
	unregister_hook('plugin_settings', 'addon/cthulhu_mythos_locations/cthulhu_mythos_locations.php', 'cthulhu_mythos_locations_settings');
	unregister_hook('plugin_settings_post', 'addon/cthulhu_mythos_locations/cthulhu_mythos_locations.php', 'cthulhu_mythos_locations_settings_post');


	logger("removed cthulhu_mythos_locations");
}



function cthulhu_mythos_locations_post_hook($a, &$item) {

	/**
	 *
	 * An item was posted on the local system.
	 * We are going to look for specific items:
	 *      - A status post by a profile owner
	 *      - The profile owner must have allowed our plugin
	 *
	 */

	logger('cthulhu_mythos_locations invoked');

	if(! local_user())   /* non-zero if this is a logged in user of this system */
		return;

	if(local_user() != $item['uid'])    /* Does this person own the post? */
		return;

	if($item['parent'])   /* If the item has a parent, this is a comment or something else, not a status post. */
		return;

	/* Retrieve our personal config setting */

	$active = get_pconfig(local_user(), 'cthulhu_mythos_locations', 'enable');

	if(! $active)
		return;

	/**
	 *
	 * OK, we're allowed to do our stuff.
	 * Here's what we are going to do:
	 * load the list of timezone names, and use that to generate a list of world cthulhu_mythos_locations.
	 * Then we'll pick one of those at random and put it in the "location" field for the post.
	 *
	 */

	$cthulhu_mythos_locations = array('Arkham','Carcosa','Celephais','Cerenerian Sea','Cimmeria','Cykranosh','Dunwich','Dylath-Leen','The Enchanted Woods','Hyperborea','Innsmouth','Jerusalem\'s Lot','K\'n-yan','Kingsport','Leng','Lomar','Miskatonic River','Miskatonic Universe','The Nameless City','Oriab','R\'lyeh','Sarkomand','Serannian','Severn Valley','Ulthar','Underworld','Y\'qaa'.'Yian','Yuggoth');

	$cthulhu_mythos_location = array_rand($cthulhu_mythos_locations,1);
	$item['location'] = $cthulhu_mythos_locations[$cthulhu_mythos_location];

	return;
}




/**
 *
 * Callback from the settings post function.
 * $post contains the $_POST array.
 * We will make sure we've got a valid user account
 * and if so set our configuration setting for this person.
 *
 */

function cthulhu_mythos_locations_settings_post($a,$post) {
	if(! local_user())
		return;
	if($_POST['cthulhu_mythos_locations-submit'])
		set_pconfig(local_user(),'cthulhu_mythos_locations','enable',intval($_POST['cthulhu_mythos_locations']));
}


/**
 *
 * Called from the Plugin Setting form. 
 * Add our own settings info to the page.
 *
 */



function cthulhu_mythos_locations_settings(&$a,&$s) {

	if(! local_user())
		return;

	/* Add our stylesheet to the page so we can make our settings look nice */

	$a->page['htmlhead'] .= '<link rel="stylesheet"  type="text/css" href="' . $a->get_baseurl() . '/addon/cthulhu_mythos_locations/cthulhu_mythos_locations.css' . '" media="all" />' . "\r\n";

	/* Get the current state of our config variable */

	$enabled = get_pconfig(local_user(),'cthulhu_mythos_locations','enable');

	$checked = (($enabled) ? ' checked="checked" ' : '');

	/* Add some HTML to the existing form */

	$s .= '<div class="settings-block">';
	$s .= '<h3>' . t('Cthulhu Mythos Location Settings') . '</h3>';
	$s .= '<div id="cthulhu_mythos_locations-enable-wrapper">';
	$s .= '<label id="cthulhu_mythos_locations-enable-label" for="cthulhu_mythos_locations-checkbox">' . t('Enable Cthulhu Mythos Locations Plugin') . '</label>';
	$s .= '<input id="cthulhu_mythos_locations-checkbox" type="checkbox" name="cthulhu_mythos_locations" value="1" ' . $checked . '/>';
	$s .= '</div><div class="clear"></div>';

	/* provide a submit button */

	$s .= '<div class="settings-submit-wrapper" ><input type="submit" name="cthulhu_mythos_locations-submit" class="settings-submit" value="' . t('Submit') . '" /></div></div>';

}
