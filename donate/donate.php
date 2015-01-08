<?php
/**
 * Name: Donate
 * Description: A crude, but much demanded donate plugin.  This addon is unsupported.
 * Version: 1.1
 * Author: Thomas Willingham <https://f.beardyunixer.com/profile/beardyunixer>
 */

/**

*/

function donate_install() {
    register_hook('page_end', 'addon/donate/donate.php', 'donate_button');
    register_hook('page_end', 'addon/donate/donate.php', 'donate_footer');
    logger("installed donate plugin");
}

function donate_uninstall() {
    unregister_hook('page_end', 'addon/donate/donate.php', 'donate_button');
    unregister_hook('page_end', 'addon/donate/donate.php', 'donate_footer');
    logger("uninstalled donate plugin");
}

function donate_module(){
return;
}

function donate_plugin_admin_post (&$a) {
    $method1 = ((x($_POST, 'method1')) ? notags(trim($_POST['method1'])) : '');
    $method_1_link = ((x($_POST, 'method1link')) ? notags(trim($_POST['method1link'])) : '');
    set_config('donate','method1',strip_tags($method1));
    set_config('donate','method1link',strip_tags($method_1_link));

    $method2 = ((x($_POST, 'method2')) ? notags(trim($_POST['method2'])) : '');
    $method_2_link = ((x($_POST, 'method1link')) ? notags(trim($_POST['method2link'])) : '');
    set_config('donate','method2',strip_tags($method2));
    set_config('donate','method2link',strip_tags($method_2_link));

    $method3 = ((x($_POST, 'method3')) ? notags(trim($_POST['method3'])) : '');
    $method_3_link = ((x($_POST, 'method3link')) ? notags(trim($_POST['method3link'])) : '');
    set_config('donate','method3',strip_tags($method3));
    set_config('donate','method3link',strip_tags($method_3_link));

    $method4 = ((x($_POST, 'method4')) ? notags(trim($_POST['method4'])) : '');
    $method_4_link = ((x($_POST, 'method1link')) ? notags(trim($_POST['method4link'])) : '');
    set_config('donate','method4',strip_tags($method4));
    set_config('donate','method4link',strip_tags($method_4_link));

    $method5 = ((x($_POST, 'method5')) ? notags(trim($_POST['method5'])) : '');
    $method_5_link = ((x($_POST, 'method1link')) ? notags(trim($_POST['method5link'])) : '');
    set_config('donate','method5',strip_tags($method5));
    set_config('donate','method5link',strip_tags($method_5_link));

    info( t('Settings updated.'). EOL );
}
function donate_plugin_admin (&$a, &$o) {
    $t = get_markup_template( "admin.tpl", "addon/donate/" );
    $o = replace_macros($t, array(
        '$submit' => t('Submit'),
        '$method1' => array('method1', t('Image location of your preffered payment method'), get_config('donate','method1'), t('eg http://somewebsite.com/someimage.jpg')),
        '$method_1_link' => array('method1link', t('Method one link'), get_config('donate','method1link'), t('Link to the payment gateway provided by your payment processor.')),
        '$method2' => array('method2', t('Method Two'), get_config('donate','method2'), t('Image location of your second payment method')),
        '$method_2_link' => array('method2link', t('Method two URL'), get_config('donate','method2link'), t('Link to the payment gateway provided by your payment processor.')),
        '$method3' => array('method3', t('Method Three'), get_config('donate','method3'), t('Image location of your third payment method')),
        '$method_3_link' => array('method3link', t('Method three URL'), get_config('donate','method3link'), t('Link to the payment gateway provided by your payment processor.')),
        '$method4' => array('method4', t('Method Four'), get_config('donate','method4'), t('Image location of your fourth payment method')),
        '$method_4_link' => array('method4link', t('Method four URL'), get_config('donate','method4link'), t('Link to the payment gateway provided by your payment processor.')),
        '$method5' => array('method5', t('Method Five'), get_config('donate','method5'), t('Image location of your fifth payment method')),
        '$method_5_link' => array('method5link', t('Method five URL'), get_config('donate','method5link'), t('Link to the payment gateway provided by your payment processor.')),
        
    ));
}

function donate_content($a,&$b) {

    $o .= '<h3>'.t('Donate').'</h3>';
    $o .= '<p>' .t('Public servers are not actually "free".  Someone has to put their time and effort into creating, and maintaining them, handling support requests, fixing bugs, answering support requests, etc, etc.  Access to free Friendica servers is a privilege, not a right and can cost admins a significant amount of money.  Public servers are not scalable in the long term.  The only way we can grow to support new users is with YOUR help.') . '</p>';

    $o .= '<p>' .t('It is estimated by public server admins that the cost of hosting an individual is between 1 and 2 cents per contact, per month.  This may not sound a lot, but multiply your 500 Facebook contacts by just ten users, and you can see how this is unsustainable.  Below are the payment methods your admin can accept to help spread the cost of this service and ensure Friendica public servers are available for everybody who needs one.') . '</p>';

 $o .= '<p>' .t('Donations are taken in good faith, and are non-refundable other than in cases of fraud') . '</p>';

    $method1 = get_config('donate', 'method1');
    $method_1_link = get_config('donate','method1link');
    if (strlen($method1)) {
        if (strlen($method_1_link)) {
            $tmp = '<a href="'.$method_1_link.'">' . '<img src="'.$method1.'"></a>';
        } else {
            $tmp = '';
		}
        $o .= $tmp;
}

    $method2 = get_config('donate', 'method2');
    $method_2_link = get_config('donate','method2link');
    if (strlen($method2)) {
        if (strlen($method_2_link)) {
            $tmp = '<a href="'.$method_2_link.'">' . '<img src="'.$method2.'"></a>';
        } else {
            $tmp = '';
		}
        $o .= $tmp;
}

    $method3 = get_config('donate', 'method3');
    $method_3_link = get_config('donate','method3link');
    if (strlen($method3)) {
        if (strlen($method_3_link)) {
            $tmp = '<a href="'.$method_3_link.'">' . '<img src="'.$method3.'"></a>';
        } else {
            $tmp = '';
		}
        $o .= $tmp;
}

    $method4 = get_config('donate', 'method4');
    $method_4_link = get_config('donate','method4link');
    if (strlen($method4)) {
        if (strlen($method_4_link)) {
            $tmp = '<a href="'.$method_4_link.'">' . '<img src="'.$method4.'"></a>';
        } else {
            $tmp = '';
		}
        $o .= $tmp;
}

    $method5 = get_config('donate', 'method5');
    $method_5_link = get_config('donate','method5link');
    if (strlen($method2)) {
        if (strlen($method_5_link)) {
            $tmp = '<a href="'.$method_5_link.'">' . '<img src="'.$method5.'"></a>';
        } else {
            $tmp = '';
		}
        $o .= $tmp;
}

return $o;
}

function donate_button(&$a,&$b){
  $b .= '<div style="position: fixed; bottom: 1px; left: 50px;"><a href="donate"><img src="addon/donate/donate.png"></a></div>'; }
