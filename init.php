<?php
// Add to the Contacts namespace
namespace SARE\Contacts;

/**
 * Add all files and functions that will be used on every page
 */

// Security measure to ensure the path to the current directory is set
if (! defined('ABSPATH')) {
	define('ABSPATH', __DIR__);
}

// Set path to the inc directory
define( 'INC_PATH', ABSPATH . '/inc');

// Includes

 require INC_PATH . '/db.php';
 require INC_PATH . '/Sanitize.php';
 require INC_PATH .'/Contact.php';
 require INC_PATH .'/kint.phar';

 // Add alias for kint
 function kint(...$v) {
 d( ...$v );
 }
 \Kint::$aliases[] = __NAMESPACE__ . '\kint';

