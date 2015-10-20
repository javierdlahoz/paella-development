=== Very Simple Event List ===
Contributors: Guido07111975
Version: 2.2
License: GNU General Public License v3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Requires at least: 3.7
Tested up to: 4.3
Stable tag: trunk
Tags: simple, upcoming, past, event, events, list, custom, post, type, datepicker


== Changelog ==
Version 2.2
- updated files vsel_shortcode and vsel_past_events_shortcode
- added Czech translation (thanks Jiri Bires)
- updated language files

Version 2.1
- files vsel_shortcode and vsel_past_events_shortcode: replaced echo with return (thanks Sebastian Schulte).

Version 2.0
- for frontend you can set date format in WP dashboard via Settings > General
- updated files vsel_shortcode and vsel_past_events_shortcode 
- updated FAQ
 
Version 1.9
- updated file readme

Version 1.8
- updated language files
- added French translation (thanks Claire Delavallee)
- added Portuguese translation (thanks Marta Ferreira)

Version 1.7
- added Brazilian Portuguese translation (thanks Fernando Sousa)
- added Ukrainian translation (thanks Kuda Poltava Team)

Version 1.6
- added German translation (thanks Andrea)
- updated FAQ
 
Version 1.5
- file vsel.php: changed date format in backend
- updated FAQ

Version 1.4
- request: display past events too
- added file vsel_past_events_shortcode.php: now you can display past events too
- file vsel.php: increased max input from 50 to 150 characters
- file vsel.php: added sanitize_text_field and esc_url to input
- added Swedish translation (thanks Cecilia Svensson)

Version 1.3
- file vsel.php: changed URL validation from sanitize_text_field into esc_url
- file vsel_shortcode.php: changed display of URL in frontend
- updated language files

Version 1.2
- request: add field for event URL (link)
- updated FAQ
- updated language files

Version 1.1
- added featured image
- added pagination
- several small adjustments
- updated FAQ

Version 1.0
- first stable release


== DESCRIPTION ==
This is a very simple plugin to add a list of your upcoming or past events in your WordPress blog. 

Besides the default title and description it contains event date, event time, event location, event URL and featured image. So no event categories or event tags. That's it.

Use shortcode [vsel] to display your upcoming events on a page.

Use shortcode [vsel_past_events] to display your past events on a page.

Question? Please take a look at the FAQ section.


= Translation =
Dutch, German, French, Portuguese, Brazilian Portuguese, Swedish, Czech and Ukrainian translation included. More translations are very welcome! Please contact me via my website.

= CREDITS =
Without the WordPress codex and help from the WordPress community I was not able to develop this plugin, so: thank you!

I have used <a href="http://code.tutsplus.com/tutorials/creating-upcoming-events-plugin-in-wordpress-custom-post-type-and-the-dashboard--wp-35404">this</a> tutorial for developing this plugin.

This tutorial is released under the GNU General Public License v3 or later.

Enjoy,
Guido


== INSTALLATION == 
After installation go to Events and start adding your events. On right side (Event Info) you can set event date, event time, event location, event URL and featured image. 

Use shortcode [vsel] to display your upcoming events on a page.

Use shortcode [vsel_past_events] to display your past events on a page.


== Frequently Asked Questions ==
= How can I change date format in US format? =
For frontend you can set date format in WP dashboard via Settings > General.

For backend the datepicker uses the European date format (day/month/year). To change this into US date format (year/month/day):

Open folder 'js' and file 'vsel_datepicker' and change date format in: 'yy-mm-dd'.

And open file 'vsel' and change date format in: 'Y-m-d'.

= How do I set plugin language? =
Very Simple Event List uses the WP Dashboard language, set in Settings > General. When language is not available, event will display in English.

= How can I set number of events on a page? =
VSEL plugin uses the number set in WP dashboard Settings > Reading.

= Can I leave event time, event location and event URL blank?  =
Yes, event time, event location, event URL and featured image are not required fields.

= What's an event URL and how do I enter it? =
It's just a link to another website for more info. You can enter the complete URL or without the http part (the http part will be auto added if not entered).

= Can I list both upcoming and past events? =
You can list upcoming or past events in frontend using different shortcodes. 
You should not use both shortcodes on the same page.

= How do I list upcoming and past events in a template file? =
For upcoming events use this: `<?php echo do_shortcode( '[vsel]' ); ?>` 

For past events use this: `<?php echo do_shortcode( '[vsel_past_events]' ); ?>`

= Other question or comment? =
Please open a topic in plugin forum or send me a message via my website.


== Screenshots == 
1. Very Simple Event List in frontend (using Twenty Fifteen theme).
2. Very Simple Event List in dashboard.
3. Very Simple Event List in dashboard.