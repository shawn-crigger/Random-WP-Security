<?php
/**
 * The following stuff should be added to wp-config.php while debugging it.
 */


/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
// Turn debugging on
define('WP_DEBUG', TRUE);

// Tell WordPress to log everything to /wp-content/debug.log
define('WP_DEBUG_LOG', TRUE);

// Turn off the display of error messages on your site
define('WP_DEBUG_DISPLAY', FALSE);

define('DISALLOW_FILE_EDIT', true);
define( 'DISALLOW_FILE_MODS', true );
//define( 'SAVEQUERIES', true );

/**
 * Set the post revisions unless the constant was set in wp-config.php
 */
if (!defined('WP_POST_REVISIONS')) define('WP_POST_REVISIONS', 5);

// For good measure, you can also add the follow code, which will hide errors from being displayed on-screen
@ini_set('display_errors', 1);
