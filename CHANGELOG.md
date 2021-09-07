# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## 1.0.3 - 2021-09-07
### Changed
- Optimized bundle content detection

## 1.0.2 - 2021-07-09
### Fixed
- Retrieval of view helper plugin manager on Laminas MVC and Mezzio
- Correct converting config object to array

## 1.0.1 - 2021-07-08
### Added
- Check whether config is an array and type cast

### Removed
- Hard requirement on psr/container as it is already required by laminas/laminas-servicemanager

### Fixed
- Typos

## 1.0.0 - 2021-07-06
### Added
- Initial view helper release
