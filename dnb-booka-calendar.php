<?php
/**
 * Plugin Name: Booka Calendar Shortcode
 * Plugin URI: https://davids-neighbour.com
 * Description: WordPress plugin to load a calendar containing tours from Booka
 * Version: 2.0.28
 * Author: Patrick Kollitsch
 * Author URI: https://davids-neighbour.com
 * License: GPL-3.0
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

// add tgm plugin activation plugin
require_once(plugin_dir_path(__FILE__)
    . '/lib/tgm-plugin-activation/class-tgm-plugin-activation.php');

// start titan frame work checker
//require_once(plugin_dir_path(__FILE__) . '/titan-framework/titan-framework-embedder.php');
// setup option pages
//add_action('tf_create_options', 'dnbtb_titanfw_options');

require_once(plugin_dir_path(__FILE__)
    . '/lib/plugin-update-checker/plugin-update-checker.php');
$update_url = 'https://hdtours.inkohsamui.com/dnb-booka-calendar.php';
PucFactory::buildUpdateChecker($update_url, __FILE__);

//$library = dirIndex(plugin_dir_path(__FILE__) . '/inc/');
//foreach ($library as $item) {
//  require_once(plugin_dir_path(__FILE__) . '/inc/' . $item);
//}
// adding a support link to the plugin listing
//add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'dnbtb_add_action_links');

/**
 * Class dnb_booka_calendar
 */
class DnbBookaCalendar
{

    private $addScript = false;

    /**
     * dnb_booka_calendar constructor.
     */
    public function __construct()
    {

        add_shortcode('bookacalendar', [
            $this,
            'shortcodeContent',
        ]);
        add_action('wp_footer', [
            $this,
            'addShortcodeScripts',
        ]);

    }

    /**
     * @param array $atts
     *
     * @return bool|string
     */
    public function shortcodeContent($atts = [])
    {

        $this->addScript = true;
        $uniqueId = md5(time());
        if (isset($atts['categories'])) {
            $type = 'data-categories="' . $atts['categories'] . '" ';
        } else {
            $type = '';
        }
        if (isset($atts['host'])) {
            $host = $atts['host'];
        } else {
            return false;
        }
        $output = '<div ';
        $output .= 'id="calendar' . $uniqueId . '" ';
        $output .= 'class="bookacalendar" ';
        $output .= $type;
        $output .= 'data-host="' . $host . '" ';
        $output .= '></div>';

        return $output;
    }

    /**
     * @return bool
     */
    public function addShortcodeScripts()
    {

        if (!$this->addScript) {
            return false;
        }
        wp_register_script('dnb_jquery',
            '//cdnjs.cloudflare.com/ajax/libs/jquery/3' . '.3.'
            . '1/jquery.min.js', [],
            false, true);
        wp_enqueue_script('dnb_jquery');

        wp_register_script('dnb_moment',
            '//cdnjs.cloudflare.com/ajax/libs/moment.js/2' . '.22.'
            . '2/moment.min.js',
            [], false, true);
        wp_enqueue_script('dnb_moment');

        wp_register_script('dnb_fullcalendar',
            '//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3' . '.9.'
            . '0/fullcalendar.min.js',
            [
                'dnb_jquery',
                'dnb_moment',
            ], false, true);
        wp_enqueue_script('dnb_fullcalendar');

        wp_register_script('dnb_visacalendar',
            plugin_dir_url(__FILE__) . 'booka-calendar.js', [
                'dnb_fullcalendar',
            ], false, true);
        wp_enqueue_script('dnb_visacalendar');

        wp_register_style('fullcalendar',
            '//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3' . '.9.'
            . '0/fullcalendar.min.css');
        wp_enqueue_style('fullcalendar');
        wp_enqueue_style('fontawesome', '//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
        wp_enqueue_style('dnb-booka-fullcalendar', plugin_dir_url(__FILE__) . 'dnb-booka-calendar.css');
    }

}

new DnbBookaCalendar();
