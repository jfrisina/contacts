<?php
/**
* Edit Contact
*
*/
// Imports
require __DIR__ . '/init.php';

// Get user id from URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
	die("Invalid contact ID.");
}
// Get user ID
$sql = "SELECT * FROM contacts WHERE id = :id";
$stmt = $connection_string->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$contact = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<html>
<head>
	<title>Edit Contact</title>
</head>
<body>
<form action="edit_contact_form.php" method="post">
    <input type="hidden" name="id" value="<?= htmlspecialchars($contact['id']) ?>">
	<!-- First Name -->
	<label for="firstname">First Name:</label>
	<input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars($contact['first_name']) ?>" required aria-required="true">

	<!-- Last Name -->
	<label for="lastname">Last Name:</label>
	<input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($contact['last_name']) ?>" required aria-required="true">

	<!-- Email -->
	<label for="email">Email:</label> <input type="email" id="email" name="email" value="<?= htmlspecialchars($contact['email']) ?>" required aria-required="true">
	<!-- Submit Button -->
	<button type="submit">Submit</button>

</form>
<!-- Delete Button -->
<form action="delete_contact_confirmation.php" method="post" onsubmit="return confirm('Are you sure you want to delete this contact?');">
    <input type="hidden" name="id" value="<?= htmlspecialchars($contact['id']) ?>">
    <button type="submit">Delete Contact</button>
</form>
</body>
</html>