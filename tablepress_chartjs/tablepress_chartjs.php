<?php
/*
Plugin Name: TablePress Extension: Chart.js
Plugin URI: https://github.com/developarts/tablepress_chartjs
Description: Extension for TablePress to create a responsive Chart.js based on the data in a TablePress table.
Version: 0.3
Author: Alejandro García
Author URI: https://developarts.com
License: GPL
*/

// Prohibit direct script loading.
defined('ABSPATH') || die('No direct script access allowed!');

/*
 * Initialize the TablePress chartjs Extension.
 */
add_action('tablepress_run', ['TablePress_Chartjs', 'init']);

/**
 * Class that contains the TablePress chartjs Extension functionality.
 *
 * @author Per Soderlind, Tobias Bäthge
 *
 * @since 0.1
 */
class TablePress_Chartjs
{
    /**
     * Version number of the Extension.
     * @var string
     */
    protected static $version = '0.3';

    /**
     * Available Shortcode attributes, without the `chartjs_` prefix.
     * @var array
     */
    protected static $shortcode_attributes = [
        'height'           => '',
        'width'            => '',
        'chart'            => 'line',
        'color'            => 'blue,red,orange,yellow,green,purple,grey,black',
        'label'            => 'a',
        'data'             => 'b',
        'last'             => '',
        'first'            => '',
    ];

    /**
     * Mapping of columns char
     * @var array
     */
    protected static $columns = array(
        'a',        'b',        'c',        'd',        'e',
        'f',        'g',        'h',        'i',        'j',
        'k',        'l',        'm',        'n',        'o',
        'p',        'q',        'r',        's',        't',
        'u',        'v',        'w',        'x',        'y',
        'z'
    );

    /**
     * Mapping colors
     * @var array
     */
    protected static $colors = array(
        'blue',
        'red',
        'orange',
        'yellow',
        'green',
        'purple',
        'grey',
        'black',
    );


    /**
     * Register necessary plugin filter hooks and the [tp-chartjs] Shortcode.
     */
    public static function init ()
    {
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_scripts_styles']);
        add_filter('tablepress_shortcode_table_default_shortcode_atts', [__CLASS__, 'register_shortcode_attributes']);
        add_filter('tablepress_table_output', [__CLASS__, 'generate_chart'], 10, 3);
        add_shortcode('tp-chartjs', [__CLASS__, 'handle_table_chart_shortcode']);
    }


    /**
     * Handle Shortcode [tp-chartjs id=<ID> /] in `the_content()`.
     *
     * @param array $shortcode_atts List of attributes that where included in the Shortcode.
     * @return string Generated HTML code for the chart with the ID <ID>.
     */
    public static function handle_table_chart_shortcode ($shortcode_atts)
    {
        // Generate the attribute query array for the template tag function.
        $table_query = [
            'chartjs' => true,
        ];

        // Pass all parameters to the template tag parameters.
        foreach ((array) $shortcode_atts as $attribute => $value) {

            // Prepend 'chartjs_' to all Shortcode attributes that the Extension understands.
            if (isset(self::$shortcode_attributes[$attribute])) {
                $attribute = 'chartjs_'.$attribute;
            }
            $table_query[$attribute] = $value;
        }

    	if (function_exists('tablepress_get_table')) {
            	return tablepress_get_table($table_query);
    	}
    }

    /**
     * Load Chart.js JavaScript and CSS files.
     */
    public static function enqueue_scripts_styles ()
    {
        global $post;
        if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'tp-chartjs')) {
            $dir = plugin_dir_url(__FILE__);
            wp_enqueue_script('chartjs', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js', ['jquery'], '', true);
            wp_enqueue_script( 'chartjs-tools', $dir . 'assets/js/tablepress_chartjs_tools.js', array( 'jquery' ), self::$version, true );
            wp_enqueue_style( 'chartjs-css', $dir . 'assets/css/tablepress_chartjs.css', array(), self::$version );
        }
    }


    /**
     * Add the Extension's parameters as valid [table /] Shortcode attributes.
     *
     * @param array $default_atts Default attributes for the TablePress [table /] Shortcode.
     * @return array Extended attributes for the Shortcode.
     */
    public static function register_shortcode_attributes ($default_atts)
    {
        $default_atts['chartjs'] = false;
        foreach (self::$shortcode_attributes as $attribute => $value) {
            $default_atts['chartjs_'.$attribute] = $value;
        }
        return $default_atts;
    }


    /**
     * Generate the HTML and JavaScript code for a chartjs chart, based on the data of the given table.
     *
     * @param string $output         The generated HTML for the table.
     * @param array  $table          The current table.
     * @param array  $render_options The render options for the table.
     *
     * @return string The generated HTML and JavaScript code for the chart.
     */
    public static function generate_chart ($output, $table, $render_options)
    {

        /*
        $table['id']
        $table['name']
        $table['description']
        $table['last_modified']
        $table['data'][0] // Column names
        $render_options['chartjs_chart']
        $render_options['chartjs_label']
        $render_options['chartjs_data']
        $render_options['chartjs_color']
        $render_options['chartjs_height']
        $render_options['chartjs_width']
        */


        if (!$render_options['chartjs']) {
            return $output;
        }

        // Declare compiling var
        $chartjs = [];
        $chartjs['options'] = array(
            'title' => $table['name'],
            'x_staked' => 'false',
            'y_staked' => 'false',
            'pointRadius' => 2,
            'borderWidth' => 3
        );


        // Determine/sanitize the chart type and add JS calculation functions.
        switch (strtolower($render_options['chartjs_chart'])) {
            case 'bar':
                $chart = 'bar';
                $chartjs['options']['borderWidth'] = 1;
                break;
            case 'hbar':
                $chart = 'horizontalBar';
                $chartjs['options']['borderWidth'] = 1;
                break;
            case 'sbar':
                $chart = 'bar';
                $chartjs['options']['x_staked'] = 'true';
                $chartjs['options']['y_staked'] = 'true';
                $chartjs['options']['borderWidth'] = 1;
                break;
            case 'hsbar':
                $chart = 'horizontalBar';
                $chartjs['options']['x_staked'] = 'true';
                $chartjs['options']['y_staked'] = 'true';
                $chartjs['options']['borderWidth'] = 1;
                break;
            case 'line':
            default:
                $chart = 'line';
                break;
        }


        $tpcjs = [];

        $tpcjs['params']['label'] = strtolower($render_options['chartjs_label']);
        $tpcjs['params']['data'] = explode(',', strtolower($render_options['chartjs_data']));
        $tpcjs['params']['columns'] = strtolower(implode(',', array($render_options['chartjs_label'], $render_options['chartjs_data'])));

        // First Validations
        if (!in_array($tpcjs['params']['label'] , self::$columns)) {
            return self::_error_box('[label]', "Declared label '{$render_options['chartjs_label']}' is not a accepted char [A-Z]");
        }
        foreach ($tpcjs['params']['data'] as $value) {
            if (!in_array($value, self::$columns)) {
                return self::_error_box('[data]', "One o some datasets declared '$value' is not a accepted char [A-Z]");
            }
        }
        if (in_array($tpcjs['params']['label'], $tpcjs['params']['data'])) {
            return self::_error_box('[data]', "You can't use label '{$render_options['chartjs_label']}' as dataset");
        }



        $colors = explode(',', $render_options['chartjs_color']);



        // data
        foreach (explode(',', $tpcjs['params']['columns']) as $letter) {
            $tempdata = [];
            $tempdata = array_column($table['data'], array_search($letter, self::$columns));
            $name = $tempdata[0];
            array_shift($tempdata);

            // Check firts or last
            if (!empty($render_options['chartjs_last']) && (int)$render_options['chartjs_last'] > 0) {
                $tempdata = array_slice($tempdata, (int)$render_options['chartjs_last'] * -1);
            } elseif (!empty($render_options['chartjs_first']) && (int)$render_options['chartjs_first'] > 0) {
                $tempdata = array_slice($tempdata, 0, (int)$render_options['chartjs_first']);
            }

            // Organice data
            if ($letter == $tpcjs['params']['label']) {
                $tpcjs['label']['name'] = $name;
                $tpcjs['label']['data'] = $tempdata;
                $tpcjs['label']['count'] = count($tempdata);
                $tpcjs['label']['json'] = json_encode(array_values($tempdata));
            } else {
                array_walk($tempdata, array('self', '_maybe_string_to_number')); // Get number values without format
                $tpcjs['sets'][$letter]['name'] = $name; // First row is the name of column
                $tpcjs['sets'][$letter]['data'] = $tempdata;
                $tpcjs['sets'][$letter]['count'] = count($tempdata);
                $tpcjs['sets'][$letter]['json'] = str_replace('"NaN"', 'NaN', json_encode(array_values($tempdata), JSON_NUMERIC_CHECK));
                $tpcjs['sets'][$letter]['color'] = current($colors);
                if (!next($colors)) { reset($colors); }
            }
        }


        // show or not pointRadius
        if ($tpcjs['label']['count'] > 50) {
            $chartjs['options']['pointRadius'] = 0;
        }


        // DataSets
        foreach ($tpcjs['sets'] as $letter => $values) {
            $chartjs['ds'][] = "{
                label: '{$values['name']}',
                fill: false,
                borderColor: window.chartColors.{$values['color']}.line,
                backgroundColor: window.chartColors.{$values['color']}.bg,
                pointRadius: {$chartjs['options']['pointRadius']},
                borderWidth: {$chartjs['options']['borderWidth']},
                data: {$values['json']},
            }";
        }
        $chartjs['rend'] = implode(',', $chartjs['ds']);

        $htmlkey = str_ireplace('-', '_', $render_options['html_id']);

        // Canvas element size
        $canvas_size = "";
        if (!empty($render_options['chartjs_width'])) {
            $canvas_size .= " width=\"{$render_options['chartjs_width']}\"";
        }
        if (!empty($render_options['chartjs_height'])) {
            $canvas_size .= " height=\"{$render_options['chartjs_height']}\"";
        }

        $chartjs_divtag = "<div style=\"width:100%;\"><canvas id=\"canvas_{$htmlkey}\"$canvas_size></canvas></div>\n";


        $chartjs_script = <<<JS
<script type="text/javascript">
    jQuery(document).ready(function(){
        var config_{$htmlkey} = {
			type: '{$chart}',
			data: {
				labels: {$tpcjs['label']['json']},
				datasets: [{$chartjs['rend']}]
			},
			options: {
				responsive: true,
				title: {display: true, text: '{$chartjs['options']['title']}'},
				tooltips: {intersect: false, mode: 'index'},
				hover: {intersect: true, mode: 'nearest'},
				scales: {
					xAxes: [{
						display: true,
                        stacked: {$chartjs['options']['x_staked']},
						scaleLabel: { display: false, labelString: ''},
                        ticks: {callback: function(value, index, values) {return tpc_axis_number_format(value);}}
					}],
					yAxes: [{
						display: true,
                        stacked: {$chartjs['options']['y_staked']},
						scaleLabel: {display: false, labelString: ''},
                        ticks: {callback: function(value, index, values) {return tpc_axis_number_format(value);}}
					}]
				}
			}
		};

        var cjs_{$htmlkey} = document.getElementById('canvas_{$htmlkey}').getContext('2d');
        window.myLine = new Chart(cjs_{$htmlkey}, config_{$htmlkey});
    });
</script>
JS;

        return $chartjs_divtag . $chartjs_script;
    }


    /**
     * Convert a string to int or float, if it's a numeric string.
     *
     * @param string $string String that shall be converted to a number.
     * @return mixed Possibly converted string.
     */
    protected static function _maybe_string_to_number(&$string)
    {
        if (empty($string)) {
            if (is_numeric($string)) {
                $string = 0;
            } else {
                $string = 'NaN';
            }
        } elseif (preg_match('/^[0-9.,$\- ]+$/', $string)) {
            $string = filter_var($string, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            if ($string == (int) $string) {
                $string = (int) $string;
            } else {
                return (float) $string;
            }
        }

    }


    protected static function array_sort($array, $on, $order=SORT_ASC)
    {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                break;
                case SORT_DESC:
                    arsort($sortable_array);
                break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }

    protected static function _error_box ($param, $error, $type='warn') {
        return "<div class=\"tablepress_chartjs_box $type\"><p><b>$param:</b> $error</p></div>";
    }


} // class TablePress_Chartjs
