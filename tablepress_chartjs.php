<?php
/*
Plugin Name: TablePress Extension: Chart.js
Plugin URI: https://github.com/developarts/tablepress_chartjs
Description: Extension for TablePress to create a responsive chart based on the data in a TablePress table.
Version: 0.1
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
     *
     * @since 0.1
     *
     * @var string
     */
    protected static $version = '0.1';

    /**
     * Available Shortcode attributes, without the `chartjs_` prefix.
     *
     * @since 0.2
     *
     * @var array
     */
    protected static $shortcode_attributes = [
        'low'              => null,
        'high'             => null,
        'width'            => '',
        'height'           => '',
        'chart'            => 'line',
        'showline'         => true,
        'showarea'         => false,
        'showpoint'        => true,
        'linesmooth'       => true,
        'aspect_ratio'     => '3:4',
        'horizontal'       => false,
        'stack'            => false,
        'animation'        => false,
        'label_offset'     => false,
        'chart_padding'    => false,
        'donut_width'      => false,
        'label'            => 1,
        'data'             => ''
    ];

    /**
     * Mapping of some Shortcode parameters to their chartjsJS equivalent.
     *
     * @since 0.2
     *
     * @var array
     */
    protected static $attribute_to_js_mapping = [
        'low'              => 'low',
        'high'             => 'high',
        'showline'         => 'showLine',
        'showarea'         => 'showArea',
        'showpoint'        => 'showPoint',
        'linesmooth'       => 'lineSmooth',
        'horizontal'       => 'horizontalBars',
        'stack'            => 'stackBars',
        'label_offset'     => 'labelOffset',
        'chart_padding'    => 'chartPadding',
        'donut_width'      => 'donutWidth',
        'label'            => 'label',
        'data'             => 'data'
    ];


    /**
     * Register necessary plugin filter hooks and the [table-chart] Shortcode.
     *
     * @since 0.1
     */
    public static function init()
    {
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_scripts_styles']);
        add_filter('tablepress_shortcode_table_default_shortcode_atts', [__CLASS__, 'register_shortcode_attributes']);
        add_filter('tablepress_table_output', [__CLASS__, 'generate_chart'], 10, 3);
        add_shortcode('table-chart', [__CLASS__, 'handle_table_chart_shortcode']);
    }

    /**
     * Handle Shortcode [table-chart id=<ID> /] in `the_content()`.
     *
     * @since 0.6
     *
     * @param array $shortcode_atts List of attributes that where included in the Shortcode.
     *
     * @return string Generated HTML code for the chart with the ID <ID>.
     */
    public static function handle_table_chart_shortcode($shortcode_atts)
    {
        // Generate the attribute query array for the template tag function.
        //print_r($shortcode_atts);
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

        //print_r($attribute);

    	if (function_exists('tablepress_get_table')) {
            	return tablepress_get_table($table_query);
    	}
    }

    /**
     * Load chartjs JavaScript and CSS files.
     *
     * @since 0.1
     */
    public static function enqueue_scripts_styles()
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
     * @since 0.1
     *
     * @param array $default_atts Default attributes for the TablePress [table /] Shortcode.
     *
     * @return array Extended attributes for the Shortcode.
     */
    public static function register_shortcode_attributes($default_atts)
    {
        //print_r($default_atts);
        $default_atts['chartjs'] = false;
        foreach (self::$shortcode_attributes as $attribute => $value) {
            $default_atts['chartjs_'.$attribute] = $value;
        }
        return $default_atts;
    }

    /**
     * Generate the HTML and JavaScript code for a chartjs chart, based on the data of the given table.
     *
     * @since 0.1
     *
     * @param string $output         The generated HTML for the table.
     * @param array  $table          The current table.
     * @param array  $render_options The render options for the table.
     *
     * @return string The generated HTML and JavaScript code for the chart.
     */
    public static function generate_chart($output, $table, $render_options)
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


        // Colors
        $colors = array(
            'rgb(54, 162, 235)',     // blue
            'rgb(255, 99, 132)',     // red
			'rgb(255, 159, 64)',     // orange
			'rgb(255, 205, 86)',     // yellow
			'rgb(75, 192, 192)',     // green
			'rgb(153, 102, 255)',    // purple
			'rgb(201, 203, 207)',    // grey
        );

        // Declare
        $datasets = [];

        // Labels
        $datasets['label']['data'] = array_column($table['data'], $render_options['chartjs_label'] - 1);
        $datasets['label']['name'] = $datasets['label']['data'][0];
        array_shift($datasets['label']['data']);
        $datasets['label']['json'] = json_encode($datasets['label']['data']);

        // Data Sets
        $sdata = explode(',', $render_options['chartjs_data']);
        foreach ($sdata as $key => $value) {
            $datasets['sets'][$key]['data'] = array_column($table['data'], $value - 1);
            $datasets['sets'][$key]['name'] = $datasets['sets'][$key]['data'][0];
            array_shift($datasets['sets'][$key]['data']);
            foreach ($datasets['sets'][$key]['data'] as $ckey => $cvalue) {
                $datasets['sets'][$key]['data'][$ckey] = self::_maybe_string_to_number($cvalue);
            }
            $datasets['sets'][$key]['json'] = json_encode($datasets['sets'][$key]['data']);
        }

        $ckey = str_ireplace('-', '_', $render_options['html_id']);


        // DataSets
        foreach ($datasets['sets'] as $dkey => $dvalue) {
            $datasets['ds'][] = "{
                label: '{$dvalue['name']}',
                fill: false,
                backgroundColor: '{$colors[$dkey]}',
                borderColor: '{$colors[$dkey]}',
                pointRadius: .1,
                pointHoverRadius: 5,
                data: {$datasets['sets'][$dkey]['json']},
            }";
        }
        $datasets['rend'] = implode(',', $datasets['ds']);




        //print_r($datasets['rend']); die();

        $json_chart_options = [];

        $chartjs_divtag = "<div style=\"width:100%;\"><canvas id=\"canv_{$ckey}\"></canvas></div>\n";

        $chartjs_script = <<<JS
<script type="text/javascript">
    jQuery(document).ready(function(){
        var config_{$ckey} = {
			type: '{$chart}',
			data: {
				labels: {$datasets['label']['json']},
				datasets: [{$datasets['rend']}]
			},
			options: {
				responsive: true,
				title: {
					display: false,
					text: 'Title'
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
						scaleLabel: {
							display: false,
							labelString: ''
						},
                        ticks: {
                            callback: function(value, index, values) {
                                if (isNaN(value)) {
                                    return value;
                                } else {
                                    return Intl.NumberFormat().format((value));
                                }
                            }
                        }
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: false,
							labelString: ''
						},
                        ticks: {
                            callback: function(value, index, values) {
                                if (isNaN(value)) {
                                    return value;
                                } else {
                                    return Intl.NumberFormat().format((value));
                                }
                            }
                        }
					}]
				}
			}
		};



        var cjs_{$ckey} = document.getElementById('canv_{$ckey}').getContext('2d');
        window.myLine = new Chart(cjs_{$ckey}, config_{$ckey});


    });
</script>
JS;

        return $chartjs_divtag.$chartjs_script;
    }

    /**
     * Convert a string to int or float, if it's a numeric string.
     *
     * @since 0.6
     *
     * @param string $string String that shall be converted to a number.
     *
     * @return mixed Possibly converted string.
     */
    protected static function _maybe_string_to_number($string)
    {
        if (empty($string)) {
            return 0;
        }

        if (!is_numeric($string)) {
            return $string;
        }

        if ($string == (int) $string) { // Don't do explicit === check here!
            return (int) $string;
        } else {
            return (float) $string;
        }
    }
} // class TablePress_Chartjs
