=== ThirstyAffiliates API ===
Contributors: drsdre
Tags: thirstlink, api, rest
Requires at least: 3.7
Tested up to: 4.7.2
Stable tag: 0.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enable ThirstyAffiliates data for use in WP Core Rest API v2

== Description ==

This plugin enables access to ThirstyAffiliates data through the WordPress Rest API v2 (part of WP core since 4.7).

Two new endpoints will be made available after plugin activation:

1. Read/update the ThirstyAffiliate links on /wp-json/wp/v2/thirstylink

2. Read/update the ThirstyAffiliate categories on /wp-json/wp/v2/thirstylink-category

== Installation ==

1.  Extract the zip file and copy contents in the wp-content/plugins/ directory of your WordPress installation
2.  Login as admin and go to Plugins
3.  Activate this plugin

== Frequently Asked Questions ==

= How to update/parse thirstylink thirstyData? =

This plugin simply dumps the data from the database which is PHP serialized.
To update this data you have, to unserialize, change the data in the array and serialize it back again.

== Changelog ==

= 1.0 =
* initial release