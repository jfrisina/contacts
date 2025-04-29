<?php
namespace SARE\Contacts;
/**
 * Edit Contact
 *
 */
// Imports
require __DIR__ . '/init.php';

// Get user id from URL
$id = filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT );
if (!$id) {
    die( "Invalid contact ID" );
}

// Create a new empty contact using the Contact class
$contact = Contact::get_by( 'id', $id ); // grab id column, and give the id that you want to get
kint( $contact ); die;

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
        <input type="email" id="email" name="email" value="<?= htmlspecialchars( $contact->get('email', true)) ?>" required aria-required="true">

        <!-- Country Code -->
        <label for="countrycode">Country Code:</label>
        <input type="number" id="countrycode" name="countrycode" required value="<?= htmlspecialchars( $contact->get('country_code', false)) ?>" aria-required="true">

        <!-- Phone -->
        <label for="phone">Phone:</label> <input type="tel" id="phone" name="phone" required  value="<?= htmlspecialchars( $contact->get('phone', false)) ?>" aria-required="true">

        <!-- Submit Button -->
        <button type="submit">Submit</button>

    </form>

    <!-- Delete Button -->
    <form action="delete_contact_confirmation.php" method="post" onsubmit="return confirm('Are you sure you want to delete this contact?');">
        <input type="hidden" name="id" value="<?= htmlspecialchars( $contact->get('id', true) ) ?>">
        <button type="submit">Delete Contact</button>
    </form>
	<?php

	// Send filled form info to database
	if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
		if ( ! empty( $_POST['firstname'] ) && ! empty( $_POST['lastname'] ) && ! empty( $_POST['email'] )  && ! empty( $_POST['countrycode'] ) && ! empty( $_POST['phone'] ) ) {
			// Using placeholders to prevent SQL injection
			$sql = 'UPDATE contacts SET first_name = :firstname, last_name = :lastname, email = :email, country_code = :countrycode, phone = :phone WHERE id = :id';
			$stmt = $connection_string->prepare( $sql );

			// get form field values and assign to a variable
			$firstname = ( $_POST['firstname'] );
			$lastname = ( $_POST['lastname'] );
			$email = filter_var( $_POST['email'], FILTER_SANITIZE_EMAIL );
			$countrycode = htmlspecialchars( $_POST['countrycode'] );
			$phone = htmlspecialchars( $_POST['phone'] );

			// sanitize values with error handling
			try {
				$firstname = sanitize_text( $firstname );
			} catch ( Exception $e ) { // e stands for exception, not error.
				die( "First name failed: " . $e->getMessage() );
			}

			try {
				$lastname = sanitize_text( $lastname );
			} catch ( Exception $e ) {
				die( "Last name failed: "  . $e->getMessage() );
			}

			try {
				$email = sanitize_email( $email );
			} catch ( Exception $e ) {
				die( "Email failed: " . $e->getMessage() );
			}

			try {
				$countrycode = sanitize_text( $countrycode );
			} catch ( Exception $e ) { // e stands for exception, not error. Exception is typecasting
				die( "Country code failed: " . $e->getMessage() );
			}

			try {
				$phone = sanitize_text( $phone );
			} catch ( Exception $e ) { // e stands for exception, not error. Exception is typecasting
				die( "Phone failed: " . $e->getMessage() );
			}

			// bind param instead of execute (lets you set type, all going in as strings otherwise)
			// string is the default type, so you don't have to reiterate this, but if doing non-string, then enter the type
			$stmt->bindParam( ':firstname', $firstname );
			$stmt->bindParam( ':lastname', $lastname );
			$stmt->bindParam( ':email', $email );
			$stmt->bindParam( ':countrycode', $countrycode );
			$stmt->bindParam( ':phone', $phone );
			$stmt->bindParam( ':id', $_POST['id'], PDO::PARAM_INT );

			// Send info to database
			$success = $stmt->execute();
            if ( $success ) {
	            ?>
                <h1>Your contact has been updated!</h1>

                <!-- Submission message -->
                <p> <?= $firstname . " " . $lastname ?> has been updated! </p>

	            <?php
            }

		} else {
			echo 'Something is wrong';
		}
	}

	?>
    <p>
        <a href="/directory.php" role="button">Go to Directory</a>
        <a href="/add_contact.php" role="button">Add Another Contact</a>
        <a href="/" role="button">Home</a>
    </p>

    </body>
    </html>

<?php

/** Sanitize form field values
 * @param string $value
 *
 * @return string
 * @throws \Exception
 */
function sanitize_text( string $value ): string { // says that the end result will be a string (typecasting)

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

/** Sanitize email
 * @param string $email
 *
 * @return string
 */
function sanitize_email( string $email ): string {

	// put all to lowercase
	$value = strtolower( $email );

	// Allowed character regular expression: /[^a-z0-9+_.@-]/i.
	$value = preg_replace( '/[^a-z0-9+_.@-]/i', '', $value );

	// trim
	$value = trim( $value );

	return $value;
}