<?php
/**
 * Delete a Contact
 */

// Imports
require __DIR__ . '/init.php';

// Ensure request is POST to prevent accidental deletions
if ($_SERVER["REQUEST_METHOD"] === "POST") {
	try {
		// Validate and sanitize the ID
		$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
	} catch (Exception $e) {
		die("Invalid contact ID.");
	}

	// Prepare the DELETE statement
	$sql = "DELETE FROM contacts WHERE id = :id";
	$stmt = $connection_string->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);

	// Execute the deletion
	if ($stmt->execute()) {
		echo "<h1>Contact Deleted Successfully!</h1>";
		echo "<a href='index.php'>Go Back to Contacts</a>";
	} else {
		echo "<h1>Error: Could not delete contact.</h1>";
	}
} else {
	echo "Invalid request.";
}



