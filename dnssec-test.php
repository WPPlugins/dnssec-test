<?php
/*
Plugin Name: DNSSEC Test 
Plugin URI: http://www.liljedahl.info/projects/wp-dnssectest/ 
Description: Checks if the ISP the visitor is using supports DNSSEC and displays the result in a widget. After activating the plugin you have to activate the widget. 
Author: Markus Liljedahl
Version: 1.2.1
Author URI: http://www.liljedahl.info/
*/
/*
This plugin is totally relying on the code from Loopia AB, www.loopia.se. For more information (in Swedish) see 
http://blogg.loopia.se/2009/02/23/snalla-stjal-var-dnssec-kod/. A HOWTO is also published at 
http://blogg.loopia.se/2009/02/25/dnssec-widget-for-wordpress/.

You are free to do any changes in the code so it fits your theme but please let me know so I maby can do a global update. 

//Markus
markus(at)liljedahl.info
*/

function print_DNSSECTestHTML() {
	$options = get_option("widget_DNSSECTest");
	options_DNSSECTestCheck();

	echo '<div id="loopia_dnssec_imagediv"></div>' . PHP_EOL;
	echo '<div id="loopia_dnssec_status_div" class="init">' . PHP_EOL;
	echo '<p id="dnssec_info_init">' . $options["dnssectest_info_init"] . '</p>' . PHP_EOL;
	echo '<p id="dnssec_info_working" style="display: none;">' . $options["dnssectest_info_working"] . '</p>' . PHP_EOL;
	echo '<p id="dnssec_info_not_working" style="display: none;">' . $options["dnssectest_info_not_working"] . '</p>' . PHP_EOL;
	echo '</div>' . PHP_EOL;
}

function print_DNSSECTestToHead() {
	if(!is_admin()) {
        	$dnssectestpluginpath = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)).'/';

        	echo '<!-- DNSSECTest :: JS BEGIN -->' . PHP_EOL;
        	echo '<script type="text/javascript" src="'.$dnssectestpluginpath.'loopia_dnssec_test.js"></script>' . PHP_EOL;
        	echo '<script type="text/javascript">' . PHP_EOL;
        	echo '<!--' . PHP_EOL;
        	echo 'window.onload=function(){' . PHP_EOL;
        	echo '_loopia_dnssectest_startTest(\'loopia_dnssec_imagediv\', \'loopia_dnssec_status_div\');' . PHP_EOL;
        	echo '}' . PHP_EOL;
        	echo '//-->' . PHP_EOL;
        	echo '</script>' . PHP_EOL;
        	echo '<!-- DNSSECTest :: JS END -->' . PHP_EOL;
        	echo PHP_EOL;
        }
}

function options_DNSSECTestCheck() {
	$options = get_option("widget_DNSSECTest");

	if (!is_array( $options )) {
		$options = array(
			'dnssectest_info_title' => 'DNSSEC Test',
			'dnssectest_info_init' => 'Checking if DNSSEC is working for you.',
			'dnssectest_info_working' => 'DNSSEC is working good from your location.',
			'dnssectest_info_not_working' => 'DNSSEC is Not working from your location.'
		);
	}
}

function widget_DNSSECTest($args) {
        extract($args);
        $options = get_option("widget_DNSSECTest");
	options_DNSSECTestCheck();

        echo $before_widget;
        echo $before_title;
        echo $options["dnssectest_info_title"];
        echo $after_title;
        print_DNSSECTestHTML();
        echo $after_widget;
}

function widget_DNSSECTestControl() {
	$options = get_option("widget_DNSSECTest");
	options_DNSSECTestCheck();
	
	if ($_POST['DNSSECTestControl-Submit']) {
		$options['dnssectest_info_title'] = htmlspecialchars($_POST['DNSSECTestControl-title']);
		$options['dnssectest_info_init'] = htmlspecialchars($_POST['DNSSECTestControl-init']);
		$options['dnssectest_info_working'] = htmlspecialchars($_POST['DNSSECTestControl-working']);
		$options['dnssectest_info_not_working'] = htmlspecialchars($_POST['DNSSECTestControl-not-working']);

		update_option("widget_DNSSECTest", $options);
	}

	echo '<p>' . PHP_EOL;
	echo '<p><label for="DNSSECTestControl-title">Widget Title:</lable>' . PHP_EOL;
	echo '<input type="text" id="DNSSECTestControl-title" name="DNSSECTestControl-title" value="' . $options['dnssectest_info_title'] . '" /></p>' . PHP_EOL;
	echo '<p><label for="DNSSECTestControl-init">Text when loading:</lable>' . PHP_EOL;
	echo '<textarea id="DNSSECTestControl-init" name="DNSSECTestControl-init" cols="30" rows="2">' . $options['dnssectest_info_init'] . '</textarea></p>' . PHP_EOL;
	echo '<p><label for="DNSSECTestControl-working">Text when working:</lable>' . PHP_EOL;
	echo '<textarea id="DNSSECTestControl-working" name="DNSSECTestControl-working" cols="30" rows="2">' . $options['dnssectest_info_working'] . '</textarea></p>' . PHP_EOL;
	echo '<p><label for="DNSSECTestControl-not-working">Text when not working:</lable>' . PHP_EOL;
	echo '<textarea id="DNSSECTestControl-not-working" name="DNSSECTestControl-not-working" cols="30" rows="2">' . $options['dnssectest_info_not_working'] . '</textarea></p>' . PHP_EOL;
	echo '<input type="hidden" id="DNSSECTestControl-Submit" name="DNSSECTestControl-Submit" value="1" />' . PHP_EOL;
	echo '</p>' . PHP_EOL;
}

function init_DNSSECTest() {
	register_sidebar_widget(__('DNSSEC Test'), 'widget_DNSSECTest');
	register_widget_control(__('DNSSEC Test'), 'widget_DNSSECTestControl');
}

add_action("plugins_loaded", "init_DNSSECTest");
add_action('wp_head', 'print_DNSSECTestToHead');
?>
