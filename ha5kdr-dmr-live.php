<?php
/**
 * Plugin Name: HA5KDR DMR Live Log
 * Plugin URI: https://github.com/nonoo/ha5kdr-dmr-live
 * Description: Displays a searchable, live amateur radio Hytera DMR network log table. Data is from http://ham-dmr.de/live_dmr
 * Version: 1.0
 * Author: Nonoo
 * Author URI: http://dp.nonoo.hu/
 * License: MIT
*/

function ha5kdr_dmr_live_generate() {
	$out = '<img id="ha5kdr-dmr-live-loader" src="' . plugins_url('loader.gif', __FILE__) . '" />' . "\n";
	$out .= '<form id="ha5kdr-dmr-live-search">' . "\n";
	$out .= '	<input type="text" id="ha5kdr-dmr-live-search-string" />' . "\n";
	$out .= '	<input type="submit" id="ha5kdr-dmr-live-search-button" value="Keresés" />' . "\n";
	$out .= '</form>' . "\n";
	$out .= '<div id="ha5kdr-dmr-live-container"></div>' . "\n";
	$out .= '<script type="text/javascript">' . "\n";
	$out .= '	var dmr_live_searchfor = "";' . "\n";
	$out .= '	$(document).ready(function () {' . "\n";
	$out .= '		$("#ha5kdr-dmr-live-container").jtable({' . "\n";
	$out .= '			paging: true,' . "\n";
	$out .= '			sorting: true,' . "\n";
	$out .= '			defaultSorting: "date desc",' . "\n";
	$out .= '			actions: {' . "\n";
	$out .= '				listAction: "' . plugins_url('ha5kdr-dmr-live-getdata.php', __FILE__) . '",' . "\n";
	$out .= '			},' . "\n";
	$out .= '			fields: {' . "\n";
	$out .= '				date: { title: "' . __('Time', 'ha5kdr-dmr-live') . '" },' . "\n";
	$out .= '				callsign: { title: "' . __('Callsign', 'ha5kdr-dmr-live') . '" },' . "\n";
	$out .= '				name: { title: "' . __('Name', 'ha5kdr-dmr-live') . '" },' . "\n";
	$out .= '				callsignid: { title: "' . __('CallsignID', 'ha5kdr-dmr-live') . '" },' . "\n";
	$out .= '				repeater: { title: "' . __('Repeater', 'ha5kdr-dmr-live') . '" },' . "\n";
	$out .= '				repeaterid: { title: "' . __('RepeaterID', 'ha5kdr-dmr-live') . '", visibility: "hidden" },' . "\n";
	$out .= '				timeslot: { title: "' . __('Timeslot', 'ha5kdr-dmr-live') . '" },' . "\n";
	$out .= '				group: { title: "' . __('Group', 'ha5kdr-dmr-live') . '" },' . "\n";
	$out .= '				dtmf: { title: "' . __('DTMF', 'ha5kdr-dmr-live') . '", visibility: "hidden" },' . "\n";
	$out .= '				city: { title: "' . __('City', 'ha5kdr-dmr-live') . '" },' . "\n";
	$out .= '				country: { title: "' . __('Country', 'ha5kdr-dmr-live') . '" },' . "\n";
	$out .= '			}' . "\n";
	$out .= '		});' . "\n";
	$out .= '		function dmr_live_update_showloader() {' . "\n";
	$out .= '			$("#ha5kdr-dmr-live-loader").fadeIn();' . "\n";
	$out .= '		}' . "\n";
	$out .= '		function dmr_live_update_hideloader() {' . "\n";
	$out .= '			$("#ha5kdr-dmr-live-loader").fadeOut();' . "\n";
	$out .= '		}' . "\n";
	$out .= '		function dmr_live_update() {' . "\n";
	$out .= '			$("#ha5kdr-dmr-live-container").jtable("load", {' . "\n";
	$out .= '				searchfor: dmr_live_searchfor' . "\n";
	$out .= '			}, dmr_live_update_hideloader);' . "\n";
	$out .= '		};' . "\n";
	$out .= '		$("#ha5kdr-dmr-live-search-button").click(function (e) {' . "\n";
	$out .= '			e.preventDefault();' . "\n";
	$out .= '			dmr_live_update_showloader();' . "\n";
	$out .= '			dmr_live_searchfor = $("#ha5kdr-dmr-live-search-string").val();' . "\n";
	$out .= '			dmr_live_update();' . "\n";
	$out .= '		});' . "\n";
	$out .= '		setInterval(function() { dmr_live_update_showloader(); $("#ha5kdr-dmr-live-container").jtable("reload", dmr_live_update_hideloader); }, 5000);' . "\n";
	$out .= '		dmr_live_update();' . "\n";
	$out .= '	});' . "\n";
	$out .= '</script>' . "\n";

	return $out;
}

function ha5kdr_dmr_live_filter($content) {
    $startpos = strpos($content, '<ha5kdr-dmr-live');
    if ($startpos === false)
		return $content;

    for ($j=0; ($startpos = strpos($content, '<ha5kdr-dmr-live', $j)) !== false;) {
		$endpos = strpos($content, '>', $startpos);
		$block = substr($content, $startpos, $endpos - $startpos + 1);

		$out = ha5kdr_dmr_live_generate();

		$content = str_replace($block, $out, $content);
		$j = $endpos;
    }
    return $content;
}
load_plugin_textdomain('ha5kdr-dmr-live', false, basename(dirname(__FILE__)) . '/languages');
add_filter('the_content', 'ha5kdr_dmr_live_filter');
add_filter('the_content_rss', 'ha5kdr_dmr_live_filter');
add_filter('the_excerpt', 'ha5kdr_dmr_live_filter');
add_filter('the_excerpt_rss', 'ha5kdr_dmr_live_filter');

function ha5kdr_dmr_live_jscss() {
	echo '<link rel="stylesheet" type="text/css" media="screen" href="' . plugins_url('jtable-theme/jtable_basic.css', __FILE__) . '" />';
	echo '<link rel="stylesheet" type="text/css" media="screen" href="' . plugins_url('ha5kdr-dmr-live.css', __FILE__) . '" />';
}
add_action('wp_head', 'ha5kdr_dmr_live_jscss');
?>
