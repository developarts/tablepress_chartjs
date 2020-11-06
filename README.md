# TablePress Extension: Chart.js

Create a [Chart.js](https://www.chartjs.org/) using [TablePress](https://tablepress.org/) as data source


## About

This plugin code base and idea is a modification of [TablePress Extension: Chartist](https://github.com/soderlind/tablepress_chartist).


## Usage

Add the Shortcode `[tp-chartjs id=1 label=A data=B,C/]` to a post or page to create a chart from the TablePress ID '1' where labes are column `A`, and dataset points are columns `B` and `C`.

![TablePress Usage](assets/tp_usage.png)

Result:

![Chartjs Usage](assets/chart_usage.png)


## Attributes

 - [`id={n}`](#att_id) TablePress ID reference
 - [`label={c,...}`](#att_label) Column Axis label (default: A)
 - [`data={n,...}`](#att_data) Column(s) datasets used to populate chart (default: B)
 - [`chart=[line|hbar]`](#att_chart) Chart type (default: line)
 - [`color`](#att_color) (default: blue,red,orange,yellow,green,purple,grey,black)
 - [`height={n}`](#att_height) Declare height of `canvas` HTML object
 - [`first={n}`](#att_first) Declare that only use the first `{n}` rows of data
 - [`last={n}`](#att_last) Declare that only use the last `{n}` rows of data


### `id`<a id="att_id"></a>

TablePress ID reference

* **Example:** `[tp-chartjs id=1/]`
* **Value:** Integer `id`


### `label`<a id="att_label"></a>

Column Axis label

* **Example:** `[tp-chartjs id=1 label=A data=B,C/]`
* **Value:** One character from [A-Z]
* **Default:** `A`


### `data`<a id="att_data"></a>

Column(s) datasets used to populate chart

* **Example:** `[tp-chartjs id=1 label=A data=B,C,D,E/]`
* **Values:** One or more comma separated columns character [A-Z]
* **Default:** `B`


### `chart`<a id="att_chart"></a>

Chart type used

* **Example:** `[tp-chartjs id=1 label=A data=B,C chart=line/]`
* **Values:** Select one of this
    * `line`: Line
    * `hbar`: Vertical Bar
* **Default:** `line`


### `color`<a id="att_color"></a>

The colors you can use in populated lines or bars.

* **Example:** `[tp-chartjs id=1 label=A data=B,C color=blue,red/]`
* **Values:** One or more comma separated colors
    * `blue`
    * `red`
    * `orange`
    * `yellow`
    * `green`
    * `purple`
    * `grey`
    * `black`
* **Default:** `blue,red,orange,yellow,green,purple,grey,black`

On example column `B` draws `blue` and column `C` draws `red`


### `height`<a id="att_height"></a>

Declare height of `canvas` HTML object

* **Example:** `[tp-chartjs id=1 label=A data=B,C height=300/]`
* **Values:** Integer value transformed into pixels
* **Default:** Automatic

### `first`<a id="att_first"></a>

Declare that only use the first `{n}` rows of data

* **Example:** `[tp-chartjs id=1 label=A data=B,C first=10/]`
* **Value:** Integer value offset
* **Default:** All data


### `last`<a id="att_last"></a>

Declare that only use the last `{n}` rows of data

* **Example:** `[tp-chartjs id=1 label=A data=B,C last=10/]`
* **Values:** Integer value offset
* **Default:** All data


## Install

Prerequisite: The [TablePress](https://tablepress.org/) plugin


## Donate to Developer

If you like my work, please donate to help me.

[![Donate to Developer](assets/button-donate.png)](https://www.paypal.com/donate?hosted_button_id=ZXY9DM6PTWB8C)


## To Do

- [ ] Chart Title
- [ ] Vertical Bar chart support
- [ ] Pie chart support
- [ ] Time Series chart support
- [ ] Stacked chart support


## Changelog

### [0.2] - 2020-11-06

#### Added
- Attribute `height` for the canvas object
- Attribute `color={color1,color2,..n}` comma separator
- Supported colors [blue,red,orange,yellow,green,purple,grey,black]
- Attribute `first={n}` show only `{n}` first rows
- Attribute `last={n}` show only `{n}` last rows

#### Changed
- Attribute `data` now accept A-Z to correspond TablePress columns
- Attribute `dimension` to `label` attribute
- `_maybe_string_to_number` RegExp


### [0.1] - 2020-11-01
- Initial release


## Credits

* Muriz Serifovic for creating [TablePress Extension: Chartist](https://github.com/soderlind/tablepress_chartist).
* Tobias Bäthge for creating [TablePress](https://tablepress.org/)
* Alejandro García [DevelopArts](https://github.com/developarts)
