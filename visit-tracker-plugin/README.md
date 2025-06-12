# Visit Tracker WordPress Plugin

**Contributors:** Jules AI Assistant
**Tags:** visits, tracker, analytics, shortcode, site stats
**Requires at least:** 5.0
**Tested up to:** 6.4
**Stable tag:** 1.0.0
**License:** GPLv2 or later
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html

Tracks site visits and displays weekly/monthly visitor counts using simple shortcodes.

## Description

The Visit Tracker plugin is a lightweight solution for monitoring traffic to your WordPress site. It records every visit and provides two shortcodes to display statistics:

*   `[site_visits_weekly]` - Shows the total number of visits in the last 7 days.
*   `[site_visits_monthly]` - Shows the total number of visits in the current calendar month.

The plugin creates a custom database table (`wp_site_visits`) to store visit data (timestamp and IP address). This table is automatically created upon plugin activation and removed upon deactivation.

## Installation

1.  **Upload the plugin files:**
    *   Download the `visit-tracker-plugin` directory.
    *   Upload the `visit-tracker-plugin` directory to the `/wp-content/plugins/` directory on your WordPress installation.
    *   Alternatively, you can zip the `visit-tracker-plugin` directory and upload it via the WordPress admin panel (Plugins > Add New > Upload Plugin).
2.  **Activate the plugin:**
    *   Navigate to the Plugins page in your WordPress admin area.
    *   Locate "Visit Tracker" in the list and click "Activate".

Upon activation, the plugin will create the necessary database table to start tracking visits.

## Usage

To display the visit counts on your posts, pages, or widgets that support shortcodes, use the following:

*   For weekly visits: `[site_visits_weekly]`
*   For monthly visits: `[site_visits_monthly]`

Simply insert these shortcodes into the WordPress editor where you want the counts to appear.

## Frequently Asked Questions

**Q: Does this plugin track unique visitors?**
A: No, this version of the plugin counts every page load as a visit. It does not distinguish between unique and repeat visitors based on IP address alone for count aggregation.

**Q: What information is stored?**
A: The plugin stores a timestamp and the visitor's IP address for each visit.

**Q: Will this plugin slow down my site?**
A: The plugin is designed to be lightweight. The visit tracking operation is a simple database insert. However, on extremely high-traffic sites, any additional database operation could have a minor impact.

## Changelog

### 1.0.0
* Initial release.
* Tracks site visits (timestamp and IP address).
* Provides `[site_visits_weekly]` and `[site_visits_monthly]` shortcodes.
* Creates and removes custom database table on activation/deactivation.
