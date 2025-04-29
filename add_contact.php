<?php
/**
 * Add a New Contact Form
 */
// Imports
require __DIR__ . '/init.php';
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
    <form action="#" method="post">
        <!-- First Name -->
        <label for="firstname">First Name:</label>
        <input type="text" id="firstname" name="firstname" required aria-required="true">

        <!-- Last Name -->
        <label for="lastname">Last Name:</label>
        <input type="text" id="lastname" name="lastname" required aria-required="true">

        <!-- Email -->
        <label for="email">Email:</label> <input type="email" id="email" name="email" required aria-required="true">

        <!-- Country Code -->
        <label for="countrycode">Country Code:</label>
        <input type="number" id="countrycode" name="countrycode" required aria-required="true">

        <!-- Phone -->
        <label for="phone">Phone:</label> <input type="tel" id="phone" name="phone" required aria-required="true">

        <!-- Submit Button -->
        <button type="submit">Submit</button>

    </form>
    </body>
    </html>
<?php

// Send filled form info to database
if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
	// Ensure all fields are filled
	if ( ! empty( $_POST['firstname'] ) && ! empty( $_POST['lastname'] ) && ! empty( $_POST['email'] ) && ! empty( $_POST['countrycode'] ) && ! empty( $_POST['phone'] ) ) {

		// Create SQL query, use placeholders for value binding to prevent SQL injection
		$sql = 'INSERT INTO contacts (first_name, last_name, email, country_code, phone) VALUES (:firstname, :lastname, :email, :countrycode, :phone)';

		// prepare SQL query using database connection
		$stmt = $connection_string->prepare( $sql );

		// Sanitize values and assign to variables
		// Sanitizing data makes it so that XSS and SQL injection can't happen. Prevents script tags from executing by escaping HTML characters so they aren't interpreted as code.
		$firstname = htmlspecialchars( $_POST['firstname'] );
		$lastname = htmlspecialchars( $_POST['lastname'] );
		$email = filter_var( $_POST['email'], FILTER_SANITIZE_EMAIL );
		$countrycode = htmlspecialchars( $_POST['countrycode'] );
		$phone = htmlspecialchars( $_POST['phone'] );

		// Bind values
		// Binding values lets you set type, all going in as strings instead. String is the default type, so you don't have to reiterate this, but if doing non-string, then enter the type
		$stmt->bindParam( ':firstname', $firstname );
		$stmt->bindParam( ':lastname', $lastname );
		$stmt->bindParam( ':email', $email );
		$stmt->bindParam( ':countrycode', $countrycode );
		$stmt->bindParam( ':phone', $phone );

		// Send info to database
		$success = $stmt->execute();
		if ( $success ) {
			?>
            <html lang="en">
            <body>
            <h1>Your contact has been added!</h1><!-- Submission message --><p> <?= $_POST['firstname'] ?> <?= $_POST['lastname'] ?> has been added to our contacts!
			<?php
		}
	} else {
		echo 'You must fill out all fields.';
	}
}
?>

    <p>
        <a href="/directory.php" role="button">Go to Directory</a>
        <a href="/add_contact.php" role="button">Add Another Contact</a> <a href="/" role="button">Home</a>
    </p>
</body>
    </html>

<?php
// Sanitize form field values
function sanitize_text( string $value ): string { // says that the end result will be a string (type hinting)

	// Check encoding to make sure it is UTF-8
	if ( mb_check_encoding( $value, "UTF-8" ) === false ) {
		throw new Exception( "This is not valid UTF-8" );
	}

	// Strip all tags
	$value = strip_tags( $value );

	// Remove white space
	$value = trim( $value );

	// Strip percent characters
	$value = str_replace( '%', '', $value );

	return $value;
}

// Sanitize email
function sanitize_email( string $email ): string {
	// put all to lowercase
	$value = strtolower( $email );

	// Allowed character regular expression: /[^a-z0-9+_.@-]/i.
	$value = preg_replace( '/[^a-z0-9+_.@-]/i', '', $value );

	// trim
	$value = trim( $value );

	return $value;
}