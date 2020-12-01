# TablePress Extension: Chart.js


Create a [Chart.js](https://www.chartjs.org/) using [TablePress](https://tablepress.org/) as data source for [WordPress](https://wordpress.org/download/)

![Banner](https://developarts.com/bl-content/uploads/banner_tablepress_chartjs.png)
![GitHub tag (latest by date)](https://img.shields.io/github/v/release/developarts/tablepress_chartjs?style=for-the-badge)
![GitHub](https://img.shields.io/github/license/developarts/tablepress_chartjs?style=for-the-badge)
[![Donate with PayPal](https://img.shields.io/badge/PayPal-Donate-yellow.svg?style=for-the-badge)](https://www.paypal.me/developarts)


## 📚 Index

- [About](#About)
- [Install](#Install)
- [Usage](#Usage)
- [Parameters](#Parameters)
    - [`id`](#param_id)
    - [`chart`](#param_chart)
    - [`label`](#param_label)
    - [`data`](#param_data)
    - [`color`](#param_color)
    - [`height`](#param_height)
    - [`first`](#param_first)
    - [`last`](#param_last)
- [Donate to Developer](#Donate)
- [ToDo](#ToDo)
- [Credits](#Credits)
- [Changelog](https://github.com/developarts/tablepress_chartjs/blob/main/CHANGELOG.md)

## 📄 About<a id="About"></a>

This plugin code base and idea is a modification of [TablePress Extension: Chartist](https://github.com/soderlind/tablepress_chartist).


## 💻 Install<a id="Install"></a>

Prerequisite: The [TablePress](https://tablepress.org/) plugin

1. [Download](https://github.com/developarts/tablepress_chartjs/releases/latest) and extract the ZIP file.
2. Upload the folder "tablepress_chartjs" to the "wp-content/plugins/" directory of your WordPress installation, e.g. via FTP.
3. Activate the plugin "TablePress Extension: Chart.js" on the "Plugins" screen of your WordPress Dashboard.


## 📈 Usage<a id="Usage"></a>

Use the Shortcode `[tp-chartjs id=N /]`

Example:

    [tp-chartjs id=1 label=A data=B,C/]

To create a chart from the TablePress ID `1` where label are column `A`, and dataset points are columns `B` and `C`.

![TablePress Usage](https://developarts.com/bl-content/uploads/tp_usage.png)

Result:

![Chartjs Usage](https://developarts.com/bl-content/uploads/chart_usage.png)


## 📑 Parameters<a id="Parameters"></a>

#### `id`<a id="param_id"></a>

TablePress ID reference

* **Example:** `[tp-chartjs id=1/]`
* **Value:** Integer `id`
* **Required**


#### `chart`<a id="param_chart"></a>

Chart type used

* **Example:** `[tp-chartjs id=1 label=A data=B,C chart=line/]`
* **Values:** Select one of this
    * `line`: Vertical
    * `bar`: Vertical Bar
    * `sbar`: Stacked Vertical Bar
    * `hbar`: Horizontal Bar
    * `hsbar`: Horizontal Stacked Bar
* **Default:** `line`


#### `label`<a id="param_label"></a>

Column Axis label

* **Example:** `[tp-chartjs id=1 label=A data=B,C/]`
* **Value:** One character from TablePress columns [A-Z]
* **Default:** `A`


#### `data`<a id="param_data"></a>

Column(s) datasets used to populate chart

* **Example:** `[tp-chartjs id=1 label=A data=B,C,D,E/]`
* **Values:** One or more comma separated columns character [A-Z]
* **Default:** `B`


#### `color`<a id="param_color"></a>

The colors you can use in populated lines or bars.

* **Example:** `[tp-chartjs id=1 label=A data=B,C color=blue,red/]`
* **Values:** One or more comma separated colors
    * ![#36a2eb](https://via.placeholder.com/15/36a2eb/000000?text=+) `blue`
    * ![#ff6384](https://via.placeholder.com/15/ff6384/000000?text=+) `red`
    * ![#ff9f40](https://via.placeholder.com/15/ff9f40/000000?text=+) `orange`
    * ![#ffcd56](https://via.placeholder.com/15/ffcd56/000000?text=+) `yellow`
    * ![#4bc0c0](https://via.placeholder.com/15/4bc0c0/000000?text=+) `green`
    * ![#9966ff](https://via.placeholder.com/15/9966ff/000000?text=+) `purple`
    * ![#c9cbcf](https://via.placeholder.com/15/c9cbcf/000000?text=+) `grey`
    * ![#000000](https://via.placeholder.com/15/000000/000000?text=+) `black`
* **Default:** `blue,red,orange,yellow,green,purple,grey,black`

On example column `B` draws `blue` and column `C` draws `red`


#### `height`<a id="param_height"></a>

Declare height of `canvas` HTML object

* **Example:** `[tp-chartjs id=1 label=A data=B,C height=300/]`
* **Values:** Integer value transformed into pixels
* **Default:** Automatic

#### `first`<a id="param_first"></a>

Declare that only use the first `{n}` rows of data

* **Example:** `[tp-chartjs id=1 label=A data=B,C first=10/]`
* **Value:** Integer value offset
* **Default:** All data


#### `last`<a id="param_last"></a>

Declare that only use the last `{n}` rows of data

* **Example:** `[tp-chartjs id=1 label=A data=B,C last=10/]`
* **Values:** Integer value offset
* **Default:** All data


## 💳 Donate to Developer<a id="Donate"></a>

If you like my work, please donate.

[![Donate to Developer](https://developarts.com/bl-content/uploads/button-donate.png)](https://www.paypal.com/donate?hosted_button_id=ZXY9DM6PTWB8C)


## ✅ ToDo<a id="ToDo"></a>

- [X] Chart Title
- [X] Vertical Bar chart support
- [X] Pie chart support
- [X] Stacked chart support
- [ ] Order data
- [ ] Detect Mobile devices
- [ ] Title of Chart show or not
- [ ] Modern Dark Theme



## 🏆 Credits<a id="Credits"></a>

* Muriz Serifovic for creating [TablePress Extension: Chartist](https://github.com/soderlind/tablepress_chartist).
* Tobias Bäthge for creating [TablePress](https://tablepress.org/)
* Alejandro García [DevelopArts](https://github.com/developarts)
