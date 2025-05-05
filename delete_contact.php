<?php
// Add to the Contacts namespace
namespace SARE\Contacts;

/**
 * Delete a Contact
 */

// Imports
require __DIR__ . '/init.php';

// Get user id from URL
$id = filter_input( INPUT_POST, 'id', FILTER_VALIDATE_INT );
if (!$id) {
	die( "Invalid contact ID" );
}

// Create a new empty contact using the Contact class
$contact = Contact::get_by( 'id', $id ); // grab id column, and give the id that you want to get

if (! $contact) {
	die ('Contact not found.');
}

if (isset($_POST['action']) && $_POST['action'] === 'delete') {
	// Send user back to the main directory page after editing a contact
	if ( $contact->delete()) { // if calling non-static method
		header( 'Location: /directory.php');
		exit;
	} else {
		echo "Could not delete this contact";
	}
}



