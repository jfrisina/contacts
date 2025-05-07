<?php
// Add to the Contacts namespace
namespace SARE\Contacts;

/**
 * Edit Contact
 *
 */

// Imports
require __DIR__ . '/init.php';

// Get user id from URL
$id = filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT );
if ( ! $id ) {
	die( "Invalid contact ID" );
}

// Create a new empty contact using the Contact class
$contact = Contact::get_by( 'id', $id ); // grab id column, and give the id that you want to get

if ( isset( $_POST['action'] ) && $_POST['action'] === 'update' ) {
	// Set variables
	$allowed_keys = [
		'first_name',
		'last_name',
		'email',
		'country_code',
		'phone',
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

	// Send user back to the main directory page after editing a contact
	if ( $contact->update( $data ) ) { // if calling non-static method
		header( 'Location: /directory.php' );
		exit;
	} else {
		echo "Could not update this contact";
	}

}
?>
    <html lang="en">
    <head>
        <title>Edit Contact</title>
    </head>
    <body>
    <form action="#" method="post">
        <input type="hidden" name="id" value="<?= (int) $contact->get('id', true) ?>">
        <!-- First Name -->
        <label for="firstname">First Name:</label>
        <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars( $contact->get('first_name', true)) ?>" required aria-required="true">

        <!-- Last Name -->
        <label for="lastname">Last Name:</label>
        <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars( $contact->get('last_name', true)) ?>" required aria-required="true">

        <!-- Email -->
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars( $contact->get( 'email', true ) ) ?>" required aria-required="true">

        <!-- Country Code -->
        <label for="country_code">Country Code: +</label>
        <input type="number" id="country_code" name="country_code" required value="<?= htmlspecialchars( $contact->get( 'country_code', false ) ) ?>" aria-required="true">

        <!-- Phone -->
        <label for="phone">Phone:</label>
        <input type="tel" id="phone" name="phone" required value="<?= htmlspecialchars( $contact->get( 'phone', false ) ) ?>" aria-required="true">

        <!-- Submit Button -->
        <button type="submit" value="update" name="action">Submit</button>

    </form>

    <!-- Delete Button -->
    <form action="delete_contact.php" method="post" onsubmit="return confirm('Are you sure you want to delete this contact?');">
        <input type="hidden" name="id" value="<?= htmlspecialchars( $contact->get( 'id', true ) ) ?>">
        <button type="submit" value="delete" name="action">Delete Contact</button>
    </form>

    </body>
    </html>
<?php