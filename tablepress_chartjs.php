<?php
/*
Plugin Name: TablePress Extension: Chart.js
Plugin URI: https://github.com/developarts/tablepress_chartjs
Description: Extension for TablePress to create a responsive Chart.js based on the data in a TablePress table.
Version: 0.2
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
 * Class that contains the TablePress Chart.js Extension functionality.
 *
 * @author Per Soderlind, Tobias Bäthge
 * @author DevelopArts, Alejandro García
 */
class TablePress_Chartjs
{
    /**
     * Version number of the Extension.
     * @var string
     */
    protected static $version = '0.2';

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
        'blue'      => 'rgb(54, 162, 235)',
        'red'       => 'rgb(255, 99, 132)',
        'orange'    => 'rgb(255, 159, 64)',
        'yellow'    => 'rgb(255, 205, 86)',
        'green'     => 'rgb(75, 192, 192)',
        'purple'    => 'rgb(153, 102, 255)',
        'grey'      => 'rgb(201, 203, 207)',
        'black'     => 'rgb(0, 0, 0)',
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

        if (!$render_options['chartjs']) {
            return $output;
        }


        // Determine/sanitize the chart type and add JS calculation functions.
        switch (strtolower($render_options['chartjs_chart'])) {
            case 'hbar':
                $chart = 'horizontalBar';
                break;
            case 'line':
            default:
                $chart = 'line';
                break;
        }


        // Declare
        $datasets = [];

        // Read all data columns
        foreach ($table['data'][0] as $key => $column) {
            $letter = self::$columns[$key]; // column letter
            $tempdata = [];

            $tempdata = array_column($table['data'], $key); // all data in array
            array_shift($tempdata); // Delete first row (name of column)

            // Check firts or last
            if (is_int((int)$render_options['chartjs_last'])) {
                $tempdata = array_slice($tempdata, (int)$render_options['chartjs_last'] * -1);
            } elseif (is_int((int)$render_options['chartjs_first'])) {
                $tempdata = array_slice($tempdata, (int)$render_options['chartjs_last']);
            }

            array_walk($tempdata, array('self', '_maybe_string_to_number')); // Get number values without format

            $datasets[$letter]['name'] = $column; // First row is the name of column
            $datasets[$letter]['data'] = $tempdata;
        }

        // Declare compiling var
        $chartjs = [];

        // label
        if (!in_array(strtolower($render_options['chartjs_label']), array_keys($datasets))) {
            // TODO manage error
        }
        $chartjs['label'] = json_encode($datasets[strtolower($render_options['chartjs_label'])]['data']);

        // Colors
        $vcolors = explode(',', $render_options['chartjs_color']);
        $acolors = [];
        foreach ($vcolors as $key => $value) {
            $acolors[] = self::$colors[$value];
        }

        // Data Sets
        $vdata = explode(',', $render_options['chartjs_data']);

        // Repeat colors to fill data
        do {
            $acolors = array_merge($acolors, $acolors);
        } while (count($acolors) <= count($vdata));


        // Data organi
        foreach ($vdata as $key => $value) {
            $letter = strtolower($value);
            if (in_array($letter, array_keys($datasets))) {
                $chartjs['sets'][] = array(
                    'title' => $datasets[$letter]['name'],
                    'color' => $acolors[$key],
                    'json' => json_encode(array_values($datasets[$letter]['data']))
                );
            }
        }


        // DataSets
        foreach ($chartjs['sets'] as $dkey => $dvalue) {
            $chartjs['ds'][] = "{
                label: '{$dvalue['title']}',
                fill: false,
                backgroundColor: '{$dvalue['color']}',
                borderColor: '{$dvalue['color']}',
                pointRadius: .1,
                pointHoverRadius: 5,
                data: {$dvalue['json']},
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
				labels: {$chartjs['label']},
				datasets: [{$chartjs['rend']}]
			},
			options: {
				responsive: true,
				title: {
                    text: '{$table['name']}',
					display: false,
				},
				tooltips: {
					mode: 'index',
					intersect: false,
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				scales: {
					xAxes: [{
						display: true,
						scaleLabel: { display: false, labelString: '', },
                        ticks: {
                            callback: function(value, index, values) {
                                if (isNaN(value)) {
                                    return value;
                                } else {
                                    return Intl.NumberFormat("es-MX").format((value));
                                }
                            }
                        }
					}],
					yAxes: [{
						display: true,
						scaleLabel: { display: false, labelString: '', },
                        ticks: {
                            callback: function(value, index, values) {
                                if (isNaN(value)) {
                                    return value;
                                } else {
                                    return Intl.NumberFormat("es-MX").format((value));
                                }
                            }
                        }
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
        if(preg_match('/^[0-9.,$ ]+$/', $string)) {
            $string = filter_var($string, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            if ($string == (int) $string) {
                $string = (int) $string;
            } else {
                return (float) $string;
            }
        } elseif (empty($string)) {
            $string = 0;
        }
    }
} // class TablePress_Chartjs
