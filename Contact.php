<?php
// Group together related classes, functions, or constants under a common label
namespace Contacts;
// Create a template for Contact objects
use InvalidArgumentException;

class Contact {
	// Properties (In a class, variables are called properties)
	private int $id;
	private string $country_code;
	private string $phone;

	// Methods (In a class, functions are called methods)

	/** Constructor Method: Create a new object from class Contact
	 *
	 * @param $id
	 * @param $country_code
	 * @param $phone
	 */
	public function __construct($id, $country_code, $phone) {
		$this->id = $id;
		$this->country_code = $country_code;
		$this->phone = $phone;
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

// Test the code
$thirteen = new Contact('13', '', '4101231234');
try {
	echo $thirteen->get('phone') . '<br>';
} catch ( InvalidArgumentException $e) {
	echo "Error: " . $e->getMessage();
}

$fourteen = new Contact('14', '44', '12345678912');
try {
	echo $fourteen->get('phone');
} catch ( InvalidArgumentException $e) {
	echo "Error: " . $e->getMessage();
}
