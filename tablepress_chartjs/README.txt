=== TablePress Extension: Chart.js ===
Contributors: developarts
Donate link: https://paypal.me/developarts
Tags: tablepress, tablepress-extension, chart, charts, chartjs
Requires at least: 1.0.0
Tested up to: 3.4
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create a Chart.js using TablePress as data source for WordPress

== Description ==

Create a Chart.js using TablePress as data source for WordPress

== Installation ==

Prerequisite: The TablePress plugin

1. Download and extract the ZIP file.
2. Upload the folder "tablepress_chartjs" to the "wp-content/plugins/" directory of your WordPress installation, e.g. via FTP.
3. Activate the plugin "TablePress Extension: Chart.js" on the "Plugins" screen of your WordPress Dashboard.


== Screenshots ==



== Changelog ==

= 0.3.0 =
* Working

= 0.2.0 =
* Parameter `height` for the canvas object
* Parameter `color={color1,color2,..n}` comma separator
* Supported colors [blue,red,orange,yellow,green,purple,grey,black]
* Parameter `first={n}` show only `{n}` first rows
* Parameter `last={n}` show only `{n}` last rows
* Parameter `data` now accept A-Z to correspond TablePress columns
* `dimension` to `label` Parameter
* `_maybe_string_to_number` RegExp

= 0.1.0 =
* Initial release

== Upgrade Notice ==

= 0.3.0 =
Working

= 0.2.0 =
Supports: color, height, first, last parameters

= 0.1.0 =
- Initial release
