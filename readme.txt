=== appear.in WP ===

Contributors: UaMV
Donate link: http://vandercar.net/wp
Tags: appear, in, video, chat, conference, webrtc, teleconference
Requires at least: 3.1
Tested up to: 4.0
Stable tag: 2.2
License: GPLv2 or later

Adds appear.in rooms to your site via shortcode

== Description ==

Harness the power of [appear.in](http://appear.in "appear.in") by embedding secure peer-to-peer video chat rooms on a self-hosted WordPress site via the [appear_in] shortcode.

= Shortcode =

> **[appear_in]**<br /><br />
> **[appear_in room="_custom-public-room-name_"]**<br />
> **[appear_in type="_public,private,post_"]**<br />
> **[appear_in public_room_button="" private_room_button="" post_room_button=""]**

Set a custom public room name, specify which room buttons are shown on a page, and/or change the button text.

= Settings =

Custom settings for appear.in Wordpress are found on the Settings > Media admin page.

* **Room Button Color**
* **Public Room Name:** define a public room name for default use in shortcode

If a public room name has not been explicitly defined in settings or shortcode, then the default public room expires daily.

The 'post' room type will generate a public room with name of the current post.

= Documentation =

Documentation and implementation can be viewed [here](http://vandercar.net/wp/appear-in-wp).

Learn more about [appear.in](http://appear.in "appear.in") - a product of [Telenor Digital AS](http://www.telenor.com/ "Telenor Digital") built with WebRTC technologies.

= Functions =

The following function can be used to include rooms:

`aiwp_include( $args );`

Default arguments:

`$args = array(
	'room'                  => '',
	'type'                  => 'public',
	'public_room_button'    => 'Public Room',
	'private_room_button'   => 'Private Room',
	'post_room_button'      => 'Post Room',
);`

= Filters =

`aiwp_unsupported_browser_message
aiwp_room_button`

== Installation ==

1. Upload the `appear-in-wordpress` directory to `/wp-content/plugins/`
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

Silence is golden.

== Screenshots ==

1. appear.in WordPress Settings

== Changelog ==

= 2.2 =
* Fix display of notices in admin
* Fixes invitation URLs when using default permalink structure
* Auto-scrolls browser to the room when visiting from an invite
* Checks brightness of button color and sets text color accordingly

= 2.1 =
* A fix when embedding on secure sites

= 2.0 =
* Replaced email invitation form with social invitations (Facebook, Twitter, Email)
* Removed stats
* Added option to select button color

= 1.8 =
* Readme edit

= 1.7 =
* Added confirmation to leave once a session has been triggered
* CSS edits
* Added link to the non-embedded, full room at appear.in
* Fixed call to ajaxurl in certain situations.

= 1.6 =
* Fixed various PHP notices

= 1.5 =
* Added display of local invitation URL
* Updated side notice class
* Readme edits

= 1.4 =
* Modified & Refined filters
* Added shortcode parameters for button text

= 1.3 =
* Refined filters
* Added room type parameter to shortcode (defaults to public)
* Removed allowed types from options
* Added number of allowable invites parameter to shortcode
* Added room type 'post'
* Added 'aiwp_include' function for use in themes
* Fixed call to included files

= 1.2 =
* Minor readme edit to show correct version
* Removed function used during development

= 1.1 =
* Adding repository images

= 1.0 =
* Initial Release

== Upgrade Notice ==

= 2.2 =
Fixes & features

= 2.0 =
Be sure to set your button color with the added option.

= 1.7 =
Adds prevention against accidentally leaving an active session.

= 1.6 =
This update fixes various PHP notices

= 1.5 =
Added display of local invitation URL + Updated side notice class

= 1.4 =
Modified & Refined filters + Added shortcode parameters for button text

= 1.3 =
* Refined filters
* Added room type parameter to shortcode (defaults to public)
* Removed allowed types from options
* Added number of allowable invites parameter to shortcode
* Added room type 'post'
* Added 'aiwp_include' function for use in themes
* Fixed call to included files

= 1.2 =
* Minor readme edit to show correct version
* Removed function used during development

= 1.0 =
* Initial Release