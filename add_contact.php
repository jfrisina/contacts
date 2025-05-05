<?php
// Add to the Contacts namespace
namespace SARE\Contacts;

/**
 * Add a New Contact Form
 */

// Imports
require __DIR__ . '/init.php';

// Check that the action and type are correct
if (isset($_POST['action']) && $_POST['action'] === 'add') {
	// Set variables
	$allowed_keys = [
		'first_name',
		'last_name',
		'email',
		'country_code',
		'phone'
	];
	$data = [];

	// Loop through each key
	foreach ( $allowed_keys as $post_key ) {
		if ( isset( $_POST[ $post_key ] ) ) {
			// Sanitize the fields
			if ( $post_key == 'country_code' ) {
				$data[ $post_key ] = filter_var( $_POST[ $post_key ], FILTER_SANITIZE_NUMBER_INT );
			} elseif ( $post_key == 'email' ) {
				$data[ $post_key ] = Sanitize::email( $_POST[ $post_key ] );
			} else {
				$data[ $post_key ] = Sanitize::text( $_POST[ $post_key ] );
			}
		}
	}

	// Add Contact
	if ( Contact::add( $_POST )) {
		// Send to the main directory page after adding Contact
		header( 'Location: /directory.php' );
		exit;
	} else {
		echo "Could not add this contact";
	}
}
    ?>
    <html lang="en">
    <head>
        <title>Contacts</title>
    </head>
    <body>
    <!-- Page Title -->
    <h1>Add a New Contact</h1>

    <!-- Tells browser where to send form data when submitted through button -->
    <!-- POST is more secure than GET -->
    <form method="post">
        <!-- First Name -->
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required aria-required="true">

        <!-- Last Name -->
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required aria-required="true">

        <!-- Email -->
        <label for="email">Email:</label> <input type="email" id="email" name="email" required aria-required="true">

        <!-- Country Code -->
        <label for="country_code">Country Code: +</label>
        <input type="number" id="country_code" name="country_code" required aria-required="true">

        <!-- Phone -->
        <label for="phone">Phone:</label> <input type="tel" id="phone" name="phone" required aria-required="true">

        <!-- Submit Button -->
        <button type="submit" value="add" name="action">Submit</button>

    </form>
    </body>
    </html>
<?php