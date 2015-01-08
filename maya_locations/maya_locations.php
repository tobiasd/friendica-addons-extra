<?php
/**
 * Name: Maya Locations
 * Description: Set a random Maya location when posting.
 * Version: 1.0
 * Author: Mike Macgirvin <http://macgirvin.com/profile/mike>
 * Author: Thomas Willingham <https://kakste.com/profile/beardyunixer>
 * Author: Oliver Hartmann <https://toktan.org/profile/oha>
 */


function maya_locations_install() {

	/**
	 * 
	 * Our demo plugin will attach in three places.
	 * The first is just prior to storing a local post.
	 *
	 */

	register_hook('post_local', 'addon/maya_locations/maya_locations.php', 'maya_locations_post_hook');

	/**
	 *
	 * Then we'll attach into the plugin settings page, and also the 
	 * settings post hook so that we can create and update
	 * user preferences.
	 *
	 */

	register_hook('plugin_settings', 'addon/maya_locations/maya_locations.php', 'maya_locations_settings');
	register_hook('plugin_settings_post', 'addon/maya_locations/maya_locations.php', 'maya_locations_settings_post');

	logger("installed maya_locations");
}


function maya_locations_uninstall() {

	/**
	 *
	 * uninstall unregisters any hooks created with register_hook
	 * during install. It may also delete configuration settings
	 * and any other cleanup.
	 *
	 */

	unregister_hook('post_local',    'addon/maya_locations/maya_locations.php', 'maya_locations_post_hook');
	unregister_hook('plugin_settings', 'addon/maya_locations/maya_locations.php', 'maya_locations_settings');
	unregister_hook('plugin_settings_post', 'addon/maya_locations/maya_locations.php', 'maya_locations_settings_post');


	logger("removed maya_locations");
}



function maya_locations_post_hook($a, &$item) {

	/**
	 *
	 * An item was posted on the local system.
	 * We are going to look for specific items:
	 *      - A status post by a profile owner
	 *      - The profile owner must have allowed our plugin
	 *
	 */

	logger('maya_locations invoked');

	if(! local_user())   /* non-zero if this is a logged in user of this system */
		return;

	if(local_user() != $item['uid'])    /* Does this person own the post? */
		return;

	if($item['parent'])   /* If the item has a parent, this is a comment or something else, not a status post. */
		return;

	/* Retrieve our personal config setting */

	$active = get_pconfig(local_user(), 'maya_locations', 'enable');

	if(! $active)
		return;

	/**
	 *
	 * OK, we're allowed to do our stuff.
	 * Here's what we are going to do:
	 * load the list of timezone names, and use that to generate a list of world maya_locations.
	 * Then we'll pick one of those at random and put it in the "location" field for the post.
	 *
	 */

	$maya_locations = array('B\'ital','Altar de Sacrificios','Altun Ha\'','Bonampak','Calakmul','Cancuen','Caracol','Chinikiha','Comalcalco','Copan','Dos Pilas','El Chorro','El Peru','Itzan','Ixkun','Ixtutz','Lacanha','Lakamtun','Los Higos','Laxtunich','Jaina','Maasal','Machaquila','Motul de San Jose','Nakum','Naranjo','Palenque','Nimlipunit','Piedras Negras','Yokib\'','Pipa\'','Pomona','Tonina','Uaxactun','Uxmal','Ucanal','Xcalumk\'in','Yaxchilan','Yaxha','Yootz','Xibalba','Tok Tan','Na Jo Chaan','Kab\'Tan','Ch\'a Chaan','Ox Tun-Nal','El Mirador,','Peten','Koba','Seibal','Naj Tunich','Arroyo de Piedra','Quirigua','Chi\'chen Izta');

	$maya_location = array_rand($maya_locations,1);
	$item['location'] = $maya_locations[$maya_location];

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

function maya_locations_settings_post($a,$post) {
	if(! local_user())
		return;
	if($_POST['maya_locations-submit'])
		set_pconfig(local_user(),'maya_locations','enable',intval($_POST['maya_locations']));
}


/**
 *
 * Called from the Plugin Setting form. 
 * Add our own settings info to the page.
 *
 */



function maya_locations_settings(&$a,&$s) {

	if(! local_user())
		return;

	/* Add our stylesheet to the page so we can make our settings look nice */

	$a->page['htmlhead'] .= '<link rel="stylesheet"  type="text/css" href="' . $a->get_baseurl() . '/addon/maya_locations/maya_locations.css' . '" media="all" />' . "\r\n";

	/* Get the current state of our config variable */

	$enabled = get_pconfig(local_user(),'maya_locations','enable');

	$checked = (($enabled) ? ' checked="checked" ' : '');

	/* Add some HTML to the existing form */

	$s .= '<div class="settings-block">';
	$s .= '<h3>' . t('Maya Location Settings') . '</h3>';
	$s .= '<div id="maya_locations-enable-wrapper">';
	$s .= '<label id="maya_locations-enable-label" for="maya_locations-checkbox">' . t('Enable Maya Locations Plugin') . '</label>';
	$s .= '<input id="maya_locations-checkbox" type="checkbox" name="maya_locations" value="1" ' . $checked . '/>';
	$s .= '</div><div class="clear"></div>';

	/* provide a submit button */

	$s .= '<div class="settings-submit-wrapper" ><input type="submit" name="maya_locations-submit" class="settings-submit" value="' . t('Submit') . '" /></div></div>';

}
