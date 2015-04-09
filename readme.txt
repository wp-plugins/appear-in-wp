=== appear.in WP ===

Contributors: UaMV
Donate link: http://vandercar.net/wp
Tags: appear, in, video, chat, conference, webrtc, teleconference
Requires at least: 3.1
Tested up to: 4.2
Stable tag: 2.4
License: GPLv2 or later

Adds appear.in rooms to your site via shortcode

== Description ==

Harness the power of [appear.in](http://appear.in "appear.in") by embedding secure peer-to-peer video chat rooms on a self-hosted WordPress site via the [appear_in] shortcode.

= Shortcode =

> **[appear_in]**

= Shortcode Attributes =
> **room="_custom-public-room-name_"**<br />
> **type="_public,private,post_"]** _(default: public)_<br />
> **public_room_button=""** _(default: Public Room)_<br />
> **private_room_button=""** _(default: Private Room)_<br />
> **post_room_button=""]** _(default: Post Room)_<br />
> **position="_left,bottom,inline_"]** _(default: left)_<br />
> **height="_int_"]**

Set a custom public room name, specify which room buttons are shown on a page, change the button text, determine where rooms will be displayed, and set height of inline rooms.

= Settings =

Custom settings for appear.in Wordpress are found on the Settings > Media admin page.

* **Room Button Color**
* **Public Room Name:** define a public room name for default use in shortcode

If a public room name has not been explicitly defined in settings or shortcode, then the default public room expires daily.

The 'post' room type will generate a public room with name of the current post.

= Features =

Button for toggling room visibility.
Button for toggling room position.
Buttons for inviting others via Twitter, Facebook, and email.

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
	'position'              => 'left',
);`

= Filters =

`aiwp_unsupported_browser_message
aiwp_room_button`

= Constants =

`AIWP_SHOW_TOGGLE`
`AIWP_SHOW_INVITE`

== Installation ==

1. Upload the `appear-in-wp` directory to `/wp-content/plugins/`
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

Silence is golden.

== Screenshots ==

1. appear.in WordPress Settings

== Changelog ==

= 2.4 =
* Utilizes new API calls
* Adds option to position room fied to left (new default)
* Adds option to toggle room position between bottom and left
* Repositions plugin function buttons

= 2.3 =
* Adds shortcode option to position room – defaults to bottom fixed
* Allows toggling visibility of active rooms
* Adds shortcode option to set height of inline room
* Overlays invite buttons on room

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

= 2.4 =
Note that the new default position of rooms is set to be fixed left. This has been successfully tested with some themes. Some themes will have issues requiring custom CSS. Adds toggling of position and provides cleaner UI.

= 2.3 =
Note that rooms will be repositioned when active unless position="inline" is explicitly included in shortcode + Other visibility & display changes

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