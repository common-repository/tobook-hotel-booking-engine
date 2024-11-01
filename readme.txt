=== tobook Hotel Booking Engine ===
Contributors: tobook
Donate link: https://www.tobook.com/
Tags: hotel, booking, engine, reservation, offers, cart, trip, travel, accommodation, tobook
Requires at least: 4.4.2
Tested up to: 5.8
Stable tag: 1.9
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.tobook.com/

**FREE** tobook Wordpress Hotel Booking Engine - drive price-parity beating commission-free bookings through your website

== Description ==

**FREE** tobook Wordpress Hotel Booking Engine - drive price-parity beating commission-free bookings through your website.

tobook Worpress Hotel Engine is commission free, fully integrates with your own site domain, (no external hosting) and comes with no hosting or other usage fees.

With an out of the box connection to Google Hotel Ads, and a range of innovative discount option packages directly visible to google,
you can ensure that you **beat** price-parity and drive high-margin commission free bookings through your **own** site!

Core Features:

*   Comes with an out of the box connection to Google Hotel Ads.
*   Includes innovative simple to use discount options for own-bookings.
*   This allows you to ensure that your own site has the **best** "headline price" on google compared with expensive high commission OTAs,
    driving direct high-margin bookings through your site!
*   Discount options include member-only discounts visible to google for customers who sign in.
*   Bespoke OAuth one-click social sign-in options via Facebook and Google are available.
*   Fully integrates with and hosted on your site, leaving your designer in full control over branding and layout customization.
*   Seamless familiar mobile and desktop user experiences that allow customers to book quickly and easily, driving conversion.
*   Supports shopping cart based group bookings.
*   Customer login area allows customers to manage their bookings and notify you of any changes.

Supported Channel Managers:

* Active Metrics
* Arpies
* Avail Pro
* BBLiveRate
* Blastness
* Booking Booster
* Booking Expert
* Cubilis
* Ericsoft
* Fastbooking
* Figaro HDT
* GNA
* GSM Hotels
* Hermes Hotels
* HotelAdvisor.net
* Hotel Net Solutions
* Hoteltoweb
* ORCA PMS Channel Manager
* RateGain/ChannelGAIN
* RoomCloud/ParityRate
* Roomshop
* Site Minder
* STAAH
* Hotel Runner
* Vertical Booking
* WuBook
* Yield Partner
* Yield Planet

If your Channel Manager is not in the above list and you wish to get connected let us know!

[Live Demo](https://www.tobook.com/wordpress/book-rooms/)

== Installation ==

For Designers and Developers:

Installation and deployment.

* Install the plugin via the WordPress admin dashboard as usual
* Include the calender shortcode `tbengine_calendar` anywhere you would like to place a booking calendar in your navigation or landing page.
* Alternatively you may skip this step, and provide your own preferred calendars to link to the booking cart.
* Create a page with a permalink for the search and cart frontend, including the frontend via shortcode: `[tbengine_offerlist/]`
* Create a page with a permalink for the booking form frontend, including the frontend via shortcode: `[tbengine_makebooking/]`
* Go to the settings page for the plugin in the admin dashboard under: Settings -> tbengine plugin
* Add the permalink (full URL including domain) for the cart frontend with shortcode `tbengine_offerlist` under "Cart Permalink"
* Add the permalink (full URL including domain) for the booking form frontend with shortcode `tbengine_makebooking` under "Booking Form Permalink"
* Add the property's tobook HotelID (integer) under "tobook HotelID", or use HotelID 100 for testing purposes (you may make test bookings under this HotelID).
* Done!

Extra settings:

* Require CVC: (integer - 0 or 1) require customers to supply CVC on the booking form - default 1.
* Default LangID: (integer from 1 to 9) default language on all frontend elements - default 1 (en).

Calendar shortcode `tbengine_calendar` query parameters (optional):

* `LangID`: (integer) - frontend language

Calendar shortcode `tbengine_calendar` attributes (optional):

* langID: (integer) sets the LangID of the calendar widgets, overrides any other settings
* open: (right|left|center) sets the horizontal opening positions of calendar popovers (default: right)
* drop: (down|up|auto) sets the vertical opening positions of calendar popovers (default: down)

Cart permalink optional query parameters:

The cart takes various optional query parameters such as langauge, arrival and departure dates, number of adults etc to
help you integrate the booking engine into your main site. Typically you would wish to at least have a form on your front
page with a pair of calendars linking to the cart permalink. The full list of cart query paramters is:

* `Arr`: (yyyy-MM-dd format) - arrival date
* `Dep`: (yyyy-MM-dd format) - departure date
* `Adults`: (integer) - number of adults
* `Children`: (integer) - number of children
* `LangID`: (integer) - frontend language
* `CurrencyID`: (integer) - user search currency

An example cart link using the default tobook demo cart permalink that includes passing in arrival and departure dates and number of adults would look like this:

https://www.tobook.com/wordpress/book-rooms/?Arr=2021-10-24&Dep=2021-10-27&Adults=2

Both of `Arr` and `Dep` must be supplied for them to have any effect, (otherwise the cart falls back to a one night search for today's date).

List of supported LangIDs:

* 1 - English
* 2 - French
* 3 - German
* 4 - Italian
* 5 - Spanish
* 6 - Portugese
* 7 - Turkish
* 8 - Hungarian
* 9 - Dutch

Passing in CurrencyID as a paramater is a little unusual as it's unlikely to exist as a separate parameter on your
main site. For the tbengine plugin, `CurrencyID` 1 is Euro, `CurrencyID` 2 is Dollar, contact us for a full list.

Layout customization:

Layout customization is performed via CSS tweaks and overrides in the files in the plugin directory:

`tbengine/app-css/custom/engine.css` (general settings)
`tbengine/app-css/custom/calendar.css` (calendar settings)
`tbengine/app-css/custom/offer-list.css` (cart settings)
`tbengine/app-css/custom/make-booking.css` (booking form settings)

where developers may freely extend or override the parent classes in:

`tbengine/app-css/engine.css`
`tbengine/app-css/calendar.css`
`tbengine/app-css/offer-list.css`
`tbengine/app-css/make-booking.css`

without restriction or being overwritten by updates.

Note that the current CSS classes are designed to work for an out of the box WordPress install with the
default "Twenty Sixteen" theme applied. For other themes and/or custom layout styles, adjustments under
tbengine/app-css/custom/ may be necessary, this is at the discretion of the own-site developers/designers
and we do not field support questions relating to site-specific CSS customization.

== Frequently Asked Questions ==

= My Channel Manager is not listed above, what should I do? =

Contact us and let us know, we will ask you to contact your channel manager and ask them to connect
tobook, (a simple procedure for them, as our Channel Protocol is compatible with major supported
OTAs).

= I see the free plugin version supports member/customer login via Email, what about single click Facebook and Google sign-in as appears on the main tobook.com site? =

Yes this is available and we can enable Facebook and Google sign-in for your members and customers, it has some
modest bespoke hosting requirements with our Auth Providers. Contact us at `support@tobook.com` for bespoke add-ons.

== Screenshots ==

1. An example boooking cart with a group booking with discounts applied. Note the clear and simple industry standard
   interface with excellent useability characteristics, important for reassuring customers and driving conversion!
2. Simple unbeaurecratic booking form gets exactly the information needed from your customers to garuantee bookings,
   while providing seamless and informative visual feedback.
3. Signing up your property for tobook engine services and linking in your Channel Manager is simple and effective.
   Enable the "best headline price" innovative discount packages with ease!
4. Comes with optional dynamic calendar widget for inclusion on your landing page or site navigation if so desired.

== Changelog ==
= 1.9 =
* calendar start date range fix.

= 1.8 =
* calendar icon.

= 1.7 =
* jQuery compatibility shim was not working accross all environments - fixed.

= 1.6 =
* session fixes and EnginePartnerID propagation.

= 1.5 =
* tbengine_calendar flexibility fixes.

= 1.4 =
* tbengine_calendar shortcode for optional pre-cart date range picker calender widget.

= 1.3 =
* langid_field in settings.

= 1.2 =
* more wordpress guideline review tweaks.

= 1.1 =
* Wordpress guideline review tweaks incorporated.

= 1.0 =
* tobook Hotel Booking Engine - release version.

== Upgrade Notice ==

= 1.9 =
calendar start date range fix.

= 1.8 =
calendar icon.

= 1.7 =
jQuery namespace compatibility shim fixes.

= 1.6 =
session fixes and engine partner notification.

= 1.5 =
date range picker layout flexibility improvements.

= 1.4 =
New: date ranger picker for your landing page or navigation.

= 1.3 =
Default language has been introduced as a plugin configuration setting.

= 1.2 =
This is the second post wordpress guideline review version.

= 1.1 =
This is the post wordpress guideline review version.

= 1.0 =
This is the release version.


