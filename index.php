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
echo "Today is " . date("l, F j, Y") . ".";
echo "It is " . date("h:i a");
?>
<!-- Action Items -->
<a href="/add_contact.php" role="button">Add New Contact</a>
<a href="/directory.php" role="button">Contacts Directory</a>
