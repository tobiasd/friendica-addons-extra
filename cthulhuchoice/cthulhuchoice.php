<?php
/**
 * Name: Cthulhu Choice
 * Description: Additional sexual prefrences for Cthulhuists
 * Version: 1.0
 * Author: Thomas Willingham <https://kakste.com/profile/beardyunixer>
 *    - who takes no responsibility for any additional content which may appear herein
 *
 */


function cthulhuchoice_install() {

	register_hook('sexpref_selector', 'addon/cthulhuchoice/cthulhuchoice.php', 'cthulhuchoice_sexpref_selector');
}


function cthulhuchoice_uninstall() {

	unregister_hook('sexpref_selector', 'addon/cthulhuchoice/cthulhuchoice.php', 'cthulhuchoice_sexpref_selector');

}

// We aren't going to bother translating these to other languages. 


function cthulhuchoice_sexpref_selector($a,&$b) {
	if($a->config['system']['language'] == 'en') {
		$b[] = 'Hastur';
		$b[] = 'Ithaqua';
		$b[] = 'Nyarlathotep';
		$b[] = 'Zhar and Lloigor';
		$b[] = 'Cyäegha';
		$b[] = 'Nyogtha';
		$b[] = 'Shub-Niggurath';
		$b[] = 'Tsathoggua';
		$b[] = 'Aphoom-Zhah';
		$b[] = 'Cthugha';
		$b[] = 'Cthulhu';
		$b[] = 'Dagon';
		$b[] = 'Ghatanothoa';
		$b[] = 'Mother Hydra';
		$b[] = 'Zoth-Ommog';
	}
}
