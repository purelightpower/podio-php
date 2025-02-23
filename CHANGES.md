[6.1.0](#v6.1.0) / Unreleased
==================
* Bugfix: In some cases errors where raised, instead of defined exceptions if preconditions for save operations were missing (see #213).

[6.0.1](#v6.0.1) / 2021-09-24
==================
* Bugfix: Turn off Guzzle HTTP errors, Podio::request handles 4xx and 5xx errors ([#211](https://github.com/podio-community/podio-php/issues/211))

[6.0.0](#v6.0.0) / 2021-08-23
==================
* BREAKING CHANGE: Drop support for PHP 5.x and 7.0/7.1/7.2
* Support PHP 8.0
* Use Guzzle HTTP client abstraction - now this falls back to PHP streams when curl is not available.
* Added get_item_values call (#193, thanks @dougblackjr)
* Replace optional kdyby/curl-ca-bundle by composer/ca-bundle (#200)

5.1.0 / 2020-07-15
==================
* Bugfix: Assure Podio::set_debug(true) performs debug output (with Kint) in non-cli setting.
* Doc: More thorough quick start guide in README.md (#190)
* Bugfix: Force HTTP 1.1 to prevent broken requests/file uploads (#191)

5.0.0 / 2020-03-10
==================

* Using composer for Kint dependency instead of copied files
* Add PodioTagItemField type
* Feature: Constant time PodioCollection access
* Adding filter API missing file_count parameter
* Add scope to PodioOAuth
* Bugfix: rate limit header parsing (#81)


4.4.0 / 2019-06-02
==================

* This is the first release under the new package name <strong>podio-community/podio-php</strong>.
It contains several fixes and minor improvements and should generally be backwards compatible to podio/podio-php v4.3.0.
* Several fixes and improvements: https://github.com/podio-community/podio-php/compare/4.3.0...v4.4.0


4.3.0 / 2015-09-30
==================

* Add support for Flows (https://developers.podio.com/doc/flows)


4.2.0 / 2015-07-02
==================

* Add `update_reference` and `count` to `PodioTask`
* Create `PodioVoting`
* Add low memory file fetch
* Verify TLS certificates
* Minor bug fixes


4.1.0 / 2015-06-16
==================

* Fix `PodioFile` `get_raw` concatenation
* Fix user model `mail` return value
* Add votes property and support for options when getting item
* Add missing properties to Comment model
* Add description to space model
* Make upload function compatible with `PHP 5.6`
* Add activation method for platform
* Add search method for platform
* Add method for org bootstrap for platform


4.0.2 / 2014-09-29
==================

* Minor bugfixes


4.0.1 / 2014-07-17
==================

* Minor bugfixes
* Make `authenticate_with_password` actually work
* Support image downloads at different sizes


4.0.0 / 2014-05-14
==================

* Introduced PodioCollection to make it easier to work with collections. Removed field and related methods from * PodioItem and PodioApp objects. Use the new array access interface instead.
* Made Podio*Itemfield objects more intuitive to work with
* Unit tests added for PodioCollection (and subclasses), PodioObject and Podio*ItemField classes
* Improved debugging options and added Kint for debugging
* Bug fixed: Handle GET/DELETE urls with options properly.
* Made __attributes and __properties private properties of PodioObject instances to underline that they shouldn’t be used


3.0.0 / 2014-01-31
==================

* Add options to bulk delete


2.0.0 / 2012-08-28
==================

* ¯\_(ツ)_/¯
