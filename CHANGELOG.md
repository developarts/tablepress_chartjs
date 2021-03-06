# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- CDN 'Chart.min.css' version 2.9.4
- README.md Icons ;-)

### Fix
- beautify main code


## [0.3.0] - 2020-11-25

### Added
- new JavaScript tools file
- Support Vertical Bar charts
- Support Vertical Stacked Bar charts
- Support Vertical Stacked Bar charts
- Error management
- CSS file for error boxes
- Shows table title
- On Datasets now distinguishes "" (empty) as `NaN` and `0` as value
- Adding index.php with silence is golden security method

### Changed
- Colors now is controlled in JavaScript
- Color array simplificated
- Procedure to obtain datasets is now optimized
- New directory 'tablepress_chartjs' for only plugin files

### Fix
- `first` and `last` now works correctly


## [0.2.0] - 2020-11-06

### Added
- Parameter `height` for the canvas object
- Parameter `color={color1,color2,..n}` comma separator
- Supported colors [blue,red,orange,yellow,green,purple,grey,black]
- Parameter `first={n}` show only `{n}` first rows
- Parameter `last={n}` show only `{n}` last rows

### Changed
- Parameter `data` now accept A-Z to correspond TablePress columns
- `dimension` to `label` Parameter
- `_maybe_string_to_number` RegExp


## [0.1.0] - 2020-11-01

### Added
- Initial release

[Unreleased]: https://github.com/developarts/tablepress_chartjs/compare/0.3.0...HEAD
[0.3.0]: https://github.com/developarts/tablepress_chartjs/compare/0.2.0...0.3.0
[0.2.0]: https://github.com/developarts/tablepress_chartjs/compare/0.1.0...0.2.0
[0.1.0]: https://github.com/developarts/tablepress_chartjs/commit/0ae2f6e3f8bfa2c9982f9b4bdde2a1fb97fa7b67
