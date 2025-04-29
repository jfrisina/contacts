<?php
// Group together related classes, functions, or constants under a common label
namespace SARE\Contacts;

// Import global classes from PHP global namespace, can now use them without the backslash prefix. Example: \PDO can now be PDO.
use InvalidArgumentException; // PHP's built-in exception class
use PDO; // PHP's built-in database class "PHP Data Objects"

// "final" prevents extending the class
final class Contact {
	// Properties (In a class, variables are called properties)
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
	 * @param $id
	 * @param $country_code
	 * @param $phone
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
	 * Load
	 * @param string     $column
	 * @param int|string $value
	 *
	 * @return self
	 */
	public static function get_by( string $column, int|string $value ): self { // "self" refers to class you're in
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
			// Create SQL query, use placeholder :id for value binding
			$sql = 'SELECT * FROM `contacts` WHERE `' . $column . '` = :' . $column;

			// Prevent SQL injection by preparing the SQL query using the database connection
			$stmt = $connection_string->prepare( $sql );

			// Bind the actual id value to the :id placeholder
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
	 * Method: Get object by property
	 * @param string $property
	 * @param bool   $raw
	 *
	 * @return string|array
	 */
	public function get( string $property='', bool $raw = false ): string | array{
		if (! $property) {
			throw new InvalidArgumentException("There is no property named $property.");
		}
		if ( !$raw && $property === 'phone' ) {
			// Call the static function to format phone
			$value = self::format_phone($this->country_code, $this->phone);
		} else {
			$value = $this->$property;
		}
		return $value;
	}

	/**
	 * Method: Format phone number
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
			$formatted_phone = $international_prefix . $country_code . preg_replace($regex, '$1-$2-$3', $phone);
		} else {
			// International phone number
			// Set country code to user input for country code field in form
			// Format the phone number
			$formatted_phone = $international_prefix . $country_code . ' ' . $phone;
		}
		return $formatted_phone;
	}
}

//// Test the code
//$thirteen = new Contact( ['id' = 13, 'country_code' => '', 'phone' => '4101231234');
//try {
//	echo $thirteen->get('phone') . '<br>';
//} catch ( InvalidArgumentException $e) {
//	echo "Error: " . $e->getMessage();
//}
//
//$fourteen = new Contact('14', '44', '12345678912');
//try {
//	echo $fourteen->get('phone');
//} catch ( InvalidArgumentException $e) {
//	echo "Error: " . $e->getMessage();
//}
