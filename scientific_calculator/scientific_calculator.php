<?php

/**
 * Name: Scientific Calculator
 * Description: Scientific Calculator

 * Version: 1.0
 * Author: Holger Froese
 */


function scientific_calculator_install() {
    register_hook('app_menu', 'addon/scientific_calculator/scientific_calculator.php', 'scientific_calculator_app_menu');
}

function scientific_calculator_uninstall() {
    unregister_hook('app_menu', 'addon/scientific_calculator/scientific_calculator.php', 'scientific_calculator_app_menu');

}

function scientific_calculator_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="scientific_calculator">The Idiot Test 2</a></div>';
}


function scientific_calculator_module() {}

function scientific_calculator_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/scientific_calculator';

$o .= <<< EOT
<br><br>
<p align="right">
<embed src="addon/scientific_calculator/scientific_calculator.swf" quality="high" bgcolor="#000000" width="400" height="650" name="scientific_calculator" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
<br><br>
<b><br>
</p>
EOT;

return $o;
}