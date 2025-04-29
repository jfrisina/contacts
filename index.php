<?php
/**
 * Home Page
 */

// Imports
require __DIR__ . '/init.php';
?>

<!-- Page Title -->
    <h1>Contacts</h1>
<?php
// Timestamps
echo "Today is " . date("l, F j, Y") . ".<br>";
date_default_timezone_set('America/New_York');
echo "It is " . date("h:i a") . "<br>";
?>
<!-- Action Items -->
<a href="/add_contact.php" role="button">Add New Contact</a>
<a href="/directory.php" role="button">Contacts Directory</a>
