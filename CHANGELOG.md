# Changelog

All notable changes to this project will be documented in this file.

<!---
### Added
### Changed
### Deprecated
### Removed
### Fixed
### Security
-->

## Unreleased

### Added

- [#44] Diagrams displays the name of the buildfile as title.

### Removed

- Rectangle around diagrams were removed.

### Changed

- Targets' colors are sent as parameter to xslt file. 

### Fixed

- Fixed composer.json to remove Travis build error.
- #43 Behat tests fixed.

## [v2.2.0] - 2018-12-02 

### Changed

- Location of xslt files are now public.

## [v2.1.0] - 2018-12-01

### Added

- Public method `\Jawira\PhingVisualizer\Diagram::transformToPuml`, this method
is supposed to be used by other libraries or projects.

## [v2.0.0] - 2018-12-01

### Changes

- Updated readme's images with new color scheme
- [#42] xslt file has been splited in three files
- Diagram's targets are surrounded by a rectangle

## [v1.7.2] - (2018-10-23)

### Added 

- [#37] Change target background color

### Fixed

- [#34] Updated PHPUnit test with `eps` format 
- Updated Behat tests with `dummy.xml` 

## [v1.7.1] - (2018-10-13)

### Fixed

- PHPUnit tests fixed

### Updated

- Images in README.md have new color scheme

## [v1.7.0] - (2018-10-13)

### Added

- `phing-visualizer` now supports `eps` format.
- New color scheme for visualization

## [v1.6.0] - (2018-07-23)

### Changed

- Executable can be executed directly without options. Default _format_ and 
default _buildfile name_ has been added.
- Improving ./CONTRIBUTING.md

## [v1.5.1] - (2018-07-11)

### Added

- Adding more examples in readme file, all buildfiles are freely available in 
Gist and Github

## [v1.5.0] - (2018-07-05) 

### Added

- Supporting visualization of RunTargetTask

## [v1.4.5] - (2018-07-03)

### Added

- [#12] An help option (-h, --help) was added to executable 
- Code Climate badges in README.md
- Travis-ci badges in README.md

### Fixed

- Updating Makefile to download latest Phing's version, older version was not 
compatible with current buildfile

## [v1.4.4] (2018-06-08)

### Fixed

* [#28] Error loading autoload file was fixed. Executable will work when executing 
from `vendor/bin/phing-visualizer`. 

### Changed

* Improved README.md

## [v1.4.0] (2018-03-05)

### Added

* Phing visualiser now supports ForeachTask
* Created CONTRIBUTING.md 

### Fixed

* Updating files for test \Jawira\PhingVisualizer\DiagramTest::testGenerateImage

### Changed

* Readme file was updated: badges, installing instructions, example and 
executable usage

## [v1.3.1] (2017-01-23)

### Fixed

* Valid license in `composer.json`  

## [v1.3.0] (2017-01-13)

### Added

* Tests with travis-ci.org & code climate


## [v1.2.0] (2017-01-11)

### Changed

* Improved Unit tests for Diagram class


## [v1.1.0] (2017-01-08)

### Added

* Unit tests for Diagram class
* Phing support

### Changed

* Refactored Diagram class


## [v1.0.0] (2017-01-02)

### Changed

* Renaming project name from `PhingDiagram` to `PhingVisualiser`.
* Updated library usage in Readme file
* Changed signature \Jawira\PhingVisualiser\Diagram::save

## [v0.0.0] (2017-11-17)

### Added

* First functional version
* Executable in `bin` dir
* XSLT file

[#42]: https://github.com/jawira/phing-visualizer/pull/42
[#37]: https://github.com/jawira/phing-visualizer/pull/37
[#34]: https://github.com/jawira/phing-visualizer/pull/34
[#28]: https://github.com/jawira/phing-visualizer/pull/28
[#12]: https://github.com/jawira/phing-visualizer/pull/12
[v1.7.2]: https://github.com/jawira/phing-visualizer/compare/v1.7.1...v1.7.2
[v1.7.1]: https://github.com/jawira/phing-visualizer/compare/v1.7.0...v1.7.1
[v1.7.0]: https://github.com/jawira/phing-visualizer/compare/v1.5.1...v1.7.0
[v1.5.1]: https://github.com/jawira/phing-visualizer/compare/v1.5.0...v1.5.1
[v1.5.0]: https://github.com/jawira/phing-visualizer/compare/v1.4.5...v1.5.0
[v1.4.5]: https://github.com/jawira/phing-visualizer/compare/v1.4.4...v1.4.5
[v1.4.4]: https://github.com/jawira/phing-visualizer/compare/v1.4.0...v1.4.4
[v1.4.0]: https://github.com/jawira/phing-visualizer/compare/v1.3.1...v1.4.0
[v1.3.1]: https://github.com/jawira/phing-visualizer/compare/v1.3.0...v1.3.1
[v1.3.0]: https://github.com/jawira/phing-visualizer/compare/v1.2.0...v1.3.0
[v1.2.0]: https://github.com/jawira/phing-visualizer/compare/v1.1.0...v1.2.0
[v1.1.0]: https://github.com/jawira/phing-visualizer/compare/v1.0.0...v1.1.0
[v1.0.0]: https://github.com/jawira/phing-visualizer/compare/v0.0.0...v1.0.0
[v1.6.0]: https://github.com/jawira/phing-visualizer/compare/v1.5.1...v1.6.0
[v2.0.0]: https://github.com/jawira/phing-visualizer/compare/v1.7.2...v2.0.0
[v2.1.0]: https://github.com/jawira/phing-visualizer/compare/v2.0.0...v2.1.0
[v2.2.0]: https://github.com/jawira/phing-visualizer/compare/v2.1.0...v2.2.0
[#44]: https://github.com/jawira/phing-visualizer/pull/44