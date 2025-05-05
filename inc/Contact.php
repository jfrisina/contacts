<?php
// Group together related classes, functions, or constants under a common label
namespace SARE\Contacts;

// Prevent Direct Access
( defined( 'ABSPATH' ) ) || die;

// Import global classes from PHP global namespace, can now use them without the backslash prefix. Example: \PDO can now be PDO.
use InvalidArgumentException; // PHP's built-in exception class
use PDO; // PHP's built-in database class "PHP Data Objects"

// Create class for Contact. "final" prevents extending the class
 final class Contact {
	// Properties (In a class, variables are called properties)
     // "private" so that cannot be directly accessed, must go through the get method
	private int $id;
	private ?string $first_name; // Question mark makes nullable instead of string only
	private ?string $last_name;
	private ?string $email;
	private ?string $country_code;
	private ?string $phone;

	// Methods (In a class, functions are called methods)
	/** Constructor Method: Create a new object from class Contact
	 * Gets called automatically when you create a new object.
	 *
	 * @param array $data
	 */
	public function __construct( array $data ) {
		$this->id = $data['id'] ?: 0; // If the id is truthy, then use it. Otherwise, set it to 0.
		$this->first_name = $data['first_name'] ?? null; // If the first name is set and not null, then use the first name. Otherwise, set it to null.
		$this->last_name = $data['last_name'] ?? null;
		$this->email = $data['email'] ?? null;
		$this->country_code = $data['country_code'] ?? null;
		$this->phone = $data['phone'] ?? null;
	}

	/**
	 * Query the database for a contact and returns a Contact object
	 * @param string     $column
	 * @param int|string $value
	 *
	 * @return self
	 */
	public static function get_by( string $column, int | string $value ): self { // "self" refers to class you're in
		// Variables
		global $connection_string; // Set this as a global variable to let the function know to use the variable in the db.php instead of creating a new and locally scoped one.
		$data = [];

		// Make sure the columns id and email are there
		if ( in_array( $column, [
			'id',
			'email',
		] ) ) {
			// If it's the id column, then typecast the value as an integer
			if ( 'id' == $column ) {
				$value = (int) $value;
			}
			// Create SQL query, use placeholder for value binding
			$sql = 'SELECT * FROM `contacts` WHERE `' . $column . '` = :' . $column;

			// Prevent SQL injection by preparing the SQL query using the database connection
			$stmt = $connection_string->prepare( $sql );

			// Bind the actual value to the :value placeholder
			$stmt->bindParam( ':' . $column, $value );

			// Run the SQL against the database
			$stmt->execute();

			// Fetch the added database row as an associative array with the column names as keys
			$data = $stmt->fetch( PDO::FETCH_ASSOC );

			// "Yoda style" - if there is no data, then set it to an empty array
			if ( false === $data ) {
				$data = [];
			}
		}
		// Show the data
		return new Contact( $data );
	}

	/**
	 * Get Contact object
	 * @param string $property
	 * @param bool   $raw
	 *
	 * @return string|array
	 */
	public function get( string $property='', bool $raw = false ): string | array{
		if (! $property) {
			throw new InvalidArgumentException("Property name must be provided.");
		}
		$value = $this->$property;

		if ( !$raw && $property === 'phone' ) {
			$value = self::format_phone($this->country_code ?? '', $this->phone ?? '');
		}
		return $value;
	}

	 /** Add Contact object
	  *
	  *
	  * @return bool
	  */
	 public static function add(): bool {
		 // Variables
		 global $connection_string; // Set this as a global variable to let the function know to use the variable in the db.php instead of creating a new and locally scoped one.

		 // Create SQL query, use placeholders (such as :id) for value binding to prevent SQL injection
		 $sql = 'INSERT INTO contacts (first_name, last_name, email, country_code, phone) VALUES (:first_name, :last_name, :email, :country_code, :phone)';

		 // Prepare SQL query using database connection
		 $prepare_sql = $connection_string-> prepare( $sql );

		 // Sanitize values and assign to variables
		 $first_name = htmlspecialchars( $_POST['first_name'] );
		 $last_name = htmlspecialchars( $_POST['last_name'] );
		 $email = filter_var( $_POST['email'], FILTER_SANITIZE_EMAIL );
		 $country_code = htmlspecialchars( $_POST['country_code'] );
		 $phone = htmlspecialchars( $_POST['phone'] );

		 // Bind values for security, clean code, and type safety
		 $prepare_sql->bindParam( ':first_name', $first_name );
		 $prepare_sql->bindParam( ':last_name', $last_name );
		 $prepare_sql->bindParam(':email', $email );
		 $prepare_sql->bindParam( 'country_code', $country_code);
		 $prepare_sql->bindParam( ':phone', $phone );

		 // Send info to database
		 $success = $prepare_sql->execute();
		 if ( $success ) {
			 echo 'Your contact has been added to the database.';
			 $return = true;
		 } else {
			 $return = false;
		 }
		 return $return;
	 }

	 /** Update Contact object
	  * @param array $data
	  *
	  * @return bool
	  */
	 public function update( array $data ): bool {
		 // Use the global variable from db.php instead of creating a new empty one
		 global $connection_string;

		 // Make sure there is an id
		 if ( empty( $this->id ) ) { // "empty" is a built-in PHP function = not falsy, not empty string, not 0
			 $return = false;
		 } else {
			 $sql_values=[];
			 // Loop through data
			 foreach ( $data as $key => $value ) {
				 // Set key to equal the bound value. Example: `firstname` = :firstname
				 $sql_values[] = '`' . $key . '` = :' . $key;
			 }
			 // Create SQL query. Equal to: $sql = 'UPDATE `contacts` SET `first_name` = :first_name`, `last_name` = :last_name WHERE `id` = :id';
			 $sql = 'UPDATE `contacts` SET ' . implode( ', ', $sql_values ) . ' WHERE `id` = :id';

			 // Prepare SQL query using database connection
			 $stmt = $connection_string->prepare( $sql );

			 foreach ( $data as $key => $value ) {
				 // bind the values
				 $stmt->bindValue( ':' . $key, $value );
			 }
			 $stmt->bindValue( ':id', $this->id, PDO::PARAM_INT );

			 // Send info to database
			 $success = $stmt->execute();
			 if ( $success ) {
				 $return = true;
			 } else {
				 $return = false;
			 }
		 }
		 return $return;
	 }

	 public function delete(): bool {
		 // Use the global variable from db.php instead of creating a new empty one
		 global $connection_string;

		 // Make sure there is an id
		 if ( empty( $this->id ) ) { // "empty" is a built-in PHP function = not falsy, not empty string, not 0
			 $return = false;
		 } else {
			 // Create SQL query. Equal to: $sql = 'DELETE `contacts` WHERE `id` = :id';
			 $sql = 'DELETE FROM `contacts` WHERE `id` = :id';

			 // Prepare SQL query using database connection
			 $stmt = $connection_string->prepare( $sql );

			 $stmt->bindValue( ':id', $this->id, PDO::PARAM_INT );

			 // Send info to database
			 $success = $stmt->execute();
			 if ( $success ) {
				 $return = true;
			 } else {
				 $return = false;
			 }
		 }
			 return $return;
	 }

	/**
	 * Format phone number
	 * Can use this function outside of this scope as well.
	 *
	 * @todo format non-US numbers
	 *
	 * @param string $country_code
	 * @param string $phone
	 *
	 * @return string
	 */
	public static function format_phone( string $country_code, string $phone ): string {
		// Phone variables
		$international_prefix = '+';
		// US phone number
		if (strlen($phone) === 10) {
			// Set the country code to US
			$country_code = '1 ';
			// Create regex pattern for US phone number
			$regex = '/([0-9]{3})([0-9]{3})([0-9]{4})/';
			// Format the phone number
			$formatted_phone = preg_replace($regex, '$1-$2-$3', $phone);
		} else {
			// International phone number
			// Set country code to user input for country code field in form
			// Format the phone number
			$formatted_phone = $phone;
		}
		return $formatted_phone;
	}
}
