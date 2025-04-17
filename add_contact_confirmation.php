<?php
/**
 * Submission Confirmation Page for Add a New Contact Form
 */
// Imports
require __DIR__ . '/init.php';

// Send filled form info to database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if ( ! empty( $_POST['firstname'] ) && ! empty( $_POST['lastname'] ) && ! empty( $_POST['email'] ) ) {
		$sql = 'INSERT INTO contacts (first_name, last_name, email) VALUES (:firstname, :lastname, :email)'; // Using placeholders to prevent SQL injection
		$stmt = $connection_string->prepare( $sql );
        // Sanitize values and assign to variables
		// Sanitizing data makes it so that XSS and SQL injection can't happen. Prevents script tags from executing by escaping HTML characters so they aren't interpreted as code.
        $firstname = htmlspecialchars($_POST['firstname']);
        $lastname = htmlspecialchars($_POST['lastname']);
        $email = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);

        // Bind values
        // Binding values lets you set type, all going in as strings instead. String is the default type, so you don't have to reiterate this, but if doing non-string, then enter the type
        $stmt->bindParam(':firstname', $firstname,PDO::PARAM_STR);
        $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email,PDO::PARAM_STR);
		// Send info to database
		$stmt->execute();
	} else {
		echo 'You must fill out all fields.';
	}
}
?>

<html>
<head>
    <title>Contacts</title>
</head>
<body>
<h1>Your contact has been added!</h1>
<!-- Submission message -->
<p> <?= $_POST['firstname'] ?> <?= $_POST['lastname'] ?> has been added to our contacts!
    <a href="/directory.php" role="button">Go to Directory</a>
    <a href="/add_contact_form.php" role="button">Add Another Contact</a>
    <a href="/" role="button">Home</a>
</p>
</body>
</html>

