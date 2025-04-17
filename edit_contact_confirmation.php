<?php
/**
 * Submission Confirmation Page for Add a New Contact Form
 */
// Imports
require __DIR__ . '/init.php';
/** @var PDO $connection_string */ // says that the variable is coming from db.php and it is a PDO object

// Send filled form info to database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if ( ! empty( $_POST['firstname'] ) && ! empty( $_POST['lastname'] ) && ! empty( $_POST['email'] ) ) {
		$sql = 'UPDATE contacts SET first_name = :firstname, last_name = :lastname, email = :email WHERE id = :id'; // Using placeholders to prevent SQL injection
		$stmt = $connection_string->prepare( $sql );

        // get form field values and assign to a variable
		$firstname = ($_POST['firstname']);
		$lastname = ($_POST['lastname']);
		$email = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);

        // sanitize values with error handling
		try {
            $firstname = sanitize_text($firstname);
		} catch (Exception $e) { // e stands for exception, not error. Exception is typecasting
            echo 'Caught exception: ',  $e->getMessage();
            die("First name failed");
        }

        try {
            $lastname = sanitize_text($lastname);
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage();
            die("Last name failed: ");
        }

        try {
            $email = sanitize_email($email);
        } catch (Exception $e) {
            echo 'Email failed: ', $e->getMessage();
            die("Email failed: ");
        }

        // bind param instead of execute (lets you set type, all going in as strings otherwise)
		// string is the default type, so you don't have to reiterate this, but if doing non-string, then enter the type
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
		$stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
		// Send info to database
		$stmt->execute();
	} else {
		echo 'Something is wrong';
	}
}
?>

<html lang="en">
<head>
    <title>Contacts</title>
</head>
<body>
<h1>Your contact has been updated!</h1>

<!-- Submission message -->
<p> <?= $firstname . " " . $lastname ?> has been updated!
    <a href="/directory.php" role="button">Go to Directory</a>
    <a href="" role="button">Add Another Contact</a>
    <a href="" role="button">Home</a>
</p>
</body>
</html>

<?php
// Sanitize form field values
function sanitize_text( string $value):string { // says that the end result will be a string (typecasting)

	// Check encoding to make sure it is UTF-8
	if (mb_check_encoding($value, "UTF-8") === false) {
		throw new Exception("This is not valid UTF-8");
	}

	// Strip all tags
	$value = strip_tags($value);

	// Remove white space
	$value = trim($value);

	// Strip percent characters
	$value = str_replace('%', '', $value);

	return $value;
}

// Sanitize email
function sanitize_email( string $email):string {
	// put all to lowercase
	$value = strtolower($email);

	// Allowed character regular expression: /[^a-z0-9+_.@-]/i.
	$value = preg_replace('/[^a-z0-9+_.@-]/i', '', $value);

    // trim
    $value = trim($value);

    return $value;
}
?>