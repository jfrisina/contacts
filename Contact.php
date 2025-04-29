<?php
// Group together related classes, functions, or constants under a common label
namespace Contacts;
// Create a template for Contact objects
class Contact {
	// Properties (In a class, variables are called properties)
	private $id;
	private $phone;

	// Methods (In a class, functions are called methods)
	/** Constructor Method: Create a new object from class Contact
	 * @param $id
	 * @param $phone
	 */
	public function __construct($id, $phone) {
		$this->id = $id;
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
			throw new InvalidArgumentException ("There is no property named $property.");
		}
		if ( !$raw && $property === 'phone' ) {
			// Call the static function to format phone
			$value = self::format_phone($this->phone);
		} else {
			$value = $this->$property;
		}
		return $value;
	}

	/**
	 * Method: Format phone number
	 * Can use this function outside of this scope as well.
	 * @param int $phone
	 *
	 * @return string
	 * @todo format non-US numbers
	 */
	public static function format_phone( int $phone ): string {
		// Phone variables
		$international_prefix = '+';
		$country_code = '';
		// US phone number
		if (strlen($phone) == 10) {
			// Set the country code to US
			$country_code = '1 ';
			// Create regex pattern for US phone number
			$regex = '/([0-9]{3})([0-9]{3})([0-9]{4})/';
			// Format the phone number
			$formatted_phone = $international_prefix . $country_code . preg_replace($regex, '$1-$2-$3', (string) $phone);
			return $formatted_phone;
		}
		// International phone number

		return $phone;
	}
}

// Test the code
$thirteen = new Contact('13', 4101231234);
try {
	echo $thirteen->get('phone');
} catch (InvalidArgumentException $e) {
	echo "Error: " . $e->getMessage();
}
