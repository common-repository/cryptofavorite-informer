<?php
/*
Plugin Name: Cryptofavorite Informer
Plugin URI:  https://developer.wordpress.org/plugins/the-basics/
Description: Cryptofavorite Widgets.
Version:     0.1.0
Author:      WordPress.org
Author URI:  https://developer.wordpress.org/
License:     GPL2
Text Domain: cfi

Cryptofavorite Informer is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Cryptofavorite Informer is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Cryptofavorite Informer. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!defined('CFI_VER')) {
    define('CFI_VER', '0.1.0');
}

// Register public resources
function register_cfi_assets()
{
    // add styles
    wp_register_style('cfi-style', plugins_url('public/styles/cfi-style.css', __FILE__), array(), CFI_VER, 'all');
    wp_enqueue_style('cfi-style');
    // add scripts
    wp_enqueue_script('cfi-style', plugins_url('public/scripts/cfi-script.js', __FILE__), array('jquery'), CFI_VER, true);
}

// Add style for site
add_action('wp_enqueue_scripts', 'register_cfi_assets');

require_once plugin_dir_path( __FILE__ ) . 'includes/settings-page.php';

// get options
$cfi_options = get_option( 'cfi_options' );

function cfi_shortcode($atts)
{
    global $cfi_options;
    // plugin settings
    $link = $cfi_options['cfi_field_link'];
    $graph = $cfi_options['cfi_field_graph'];
    $volume = $cfi_options['cfi_field_volume'];
    $marketCap = $cfi_options['cfi_field_market_cap'];
    // shortcode options
    $coin = $atts['coin'];
    $style = $atts['style'];

    if ($coin) {
        if ($style === 'widget') {

            return '<div class="cfi-coin-ticker-widget">
                <div class="cfi-coin-ticker"
                  data-cfi-coin="'. $coin .'"
                  data-cfi-style="'. $style .'"
                  data-cfi-hide-link="'. $link .'"
                  data-cfi-graph="'. $graph .'"
                  data-cfi-volume="'. $volume .'"
                  data-cfi-market-cap="'. $marketCap .'"
                >
                    Loading...
                </div>
            </div>';

        } else { // default style ticker

            return '<div class="cfi-coin-ticker-text"
              data-cfi-coin="'. $coin .'"
              data-cfi-style="'. $style .'"
              data-cfi-hide-link="'. $link .'"
            >
                Loading...
            </div>';

        }
    } else {
        return '<span>Ticker error: Missing coin name.</span>';
    }
}

add_shortcode('cryptofavorite-ticker', 'cfi_shortcode');
