<?php
/**
 * Add a New Contact Form
 */
// Imports
require __DIR__ . '/init.php';
?>

<html>
<head>
    <title>Contacts</title>
</head>
<body>
<!-- Page Title -->
<h1>Add a New Contact</h1>

<!-- Tells browser where to send form data when submitted through button -->
<!-- POST is more secure than GET -->
<form action="add_contact_confirmation.php" method="post">
    <!-- First Name -->
    <label for="firstname">First Name:</label>
    <input type="text" id="firstname" name="firstname" required aria-required="true">

    <!-- Last Name -->
    <label for="lastname">Last Name:</label>
    <input type="text" id="lastname" name="lastname" required aria-required="true">

    <!-- Email -->
    <label for="email">Email:</label> <input type="email" id="email" name="email" required aria-required="true">

    <!-- Submit Button -->
    <button type="submit">Submit</button>

</form>
</body>
</html>