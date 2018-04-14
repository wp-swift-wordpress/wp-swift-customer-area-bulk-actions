# WP Swift: Customer Area Bulk Actions

 * Plugin Name: WP Swift: Customer Area Bulk Actions
 * Plugin URI: https://github.com/wp-swift-wordpress/wp-swift-customer-area-bulk-actions
 * Description: Adds new bulk actions to Customer Area private files.
 * Version: 1
 * Author: Gary Swift
 * Author URI: https://github.com/wp-swift-wordpress-plugins
 * License: GPL2


As of WordPress 4.7 it is possible to add custom bulk actions to any post type. See this example here: 

[https://make.wordpress.org/core/2016/10/04/custom-bulk-actions/](https://make.wordpress.org/core/2016/10/04/custom-bulk-actions/)

This is a useful feature that I wanted to use with **[WP Customer Area - Notifications](http://wp-customerarea.com)** for bulk publishing files.

However, the API does not work with customer area files as it uses it’s own URL structure.

`wp-admin/admin.php?page=wpca-list%2Ccontent%2Ccuar_private_file`

Since the standard methods of adding and handling bulk actions do not work with private files and adding JavaScript using the _admin\_enqueue\_scripts_ function seems to be disabled, I have attempted to do achieve this by adding JavaScript using the _admin\_footer_ function.

This script adds new bulk actions to the DOM and the script handles these actions using Ajax.

## Installation

Add the directory to your plugins folder and activate via the plugins admin page.

## Usage

This will add new custom bulk actions the **WP Customer Area** private files admin page.

![alt text][logo]

[logo]: image.png "New Bulk Actions"

The **Publish** bulk action works as expected.

### To-Do

The **Publish and Notify** bulk action is not yet implemented.

### Testing

There is also **Test Ajax** function that make an _ajax_ call to the server and outputs debug information to _debug.log_ Requires WordPress debugging to be turned on).