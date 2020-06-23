# Changelog

All notable changes to this project will be documented in this file.


## [1.9.2] - 2020.06.23

Config Change Release

### Changed

- Changed default view to huffpost-breaking / nyt-homepage / foxnews-latest


## [1.9.1] - 2020.06.22

Bugfix Release

### Changed

- Fixed NYT feeds config


## [1.9] - 2020.06.21

Interface and Tech Upgrade Release

### Added

- Display keywords in header
- Added hover effect on RSS link icon

### Changed

- Fixed positioning of sub-title at small widths
- Tech: Refactored list in Feeds class, added static methods
- Tech: Added formatting methods to Template class


## [1.8.1] - 2020.06.20

Functionality Upgrade Release

### Added

- Added ability to search multiple keywords

### Changed

- Changed order of elements in Settings form


## [1.8] - 2020.06.20

Functionality Upgrade Release

### Added

- Added keyword filter to user Settings

### Changed

- Tech: Refactored Reader methods


## [1.7] - 2020.06.19

**Public Beta** - Interface, Accessibility, and Feeds Upgrade Release

### Added

- Added direct link to RSS feed
- Accessibility: Added anchors
- Added counts to meta description
- Added Al Jazeera feed

### Changed

- Don't load images if user settings images=none
- Accessibility: Optimized page structure
- Fixed vertical scrollbar bugs
- Fixed Politico feed
- Tech: Made Feeds class
- Tech: Moved default feeds to Feeds class

### Removed

- Removed Reuters feeds (not working)


## [1.6] - 2020.06.14

Branding and Config Update Release

### Changed

- Changed logo and branding to "WhoNews Beta"
- Changed default view to huffpost-us / csm-usa / foxnews-national


## [1.5.1] - 2020.06.13

Config Change Release

### Changed

- Changed default view to huffpost-us / foxnews-national
- Tweaked HuffPost logo


## [1.5] - 2020.06.13

Feed Parsing Upgrade Release

### Changed

- Fixed parsing of HuffPost, NPR, USA Today images
- Improved cleaning of description
- Changed separators in page title
- Improved Reuters tab image


## [1.4] - 2020.06.13

Interface and Feed Upgrade Release

### Added

- Added Politico feeds

### Changed

- Removed anchor click from Settings button
- Changed article title font size
- Changed CNN logo
- Use feed label on tab texts / titles
- Changed arrow in feed title
- Changed default scrolling to 'sync'
- Settings: UI & label changes


## [1.3.1] - 2020.06.11

Config Change Release

### Changed

- Changed default view to cnn-us / foxnews-national


## [1.3] - 2020.06.10

Interface Upgrade and Bugfix Release

### Added

- Added logo images and titles to tabs
- Added 'limit' option to settings
- Added sliding panel effect
- Added accessible #settings anchor

### Changed

- Fix: Use 'link' instead of 'guid' for href, when needed
- Redesigned header
- Changed default target to 'new'
- Last feed shows empty 'Custom' field by default
- Updated meta description
- Updated Readme


## [1.2] - 2020.06.06

Interface and Tech Upgrade Release

### Added

- Added images-small option, as default
- Added target-new option
- Added hover effect

### Changed

- Source / Date as single line at bottom
- Color on settings button
- Removed USA Today Opinions feed
- Tech: Added constants
- Tech: Refactored functions into classes


## [1.1] - 2020.06.03

Upgrade Release

### Added

- Added meta description
- Added tabindex on Settings form
- Redirect to optimized (canonical) url

### Changed

- Removed HuffPost Opinions feed


## [1.0] - 2020.06.02

**Minimum Viable Product** - Public Release

### Added

- Controls for Settings
- More feeds


## [0.1] - 2020.05.31

Pre-public version with 3 fixed feeds

### Added

- Script to load and parse feeds
- Public web interface


