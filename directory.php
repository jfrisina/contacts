<?php
/**
 * Contacts Directory
 * Displays all contacts in a table format.
 */
// Imports
require __DIR__ . '/init.php';

// Get contacts from database
$sql = 'SELECT * FROM contacts';
$stmt = $connection_string->prepare($sql);
$stmt->execute();
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<html>
<head>
    <title>Contacts</title>
</head>
<body>
<!-- Page Title -->
<h1>Contacts Directory</h1>
<!-- Table -->
<div class="table-container" role="region" aria-labelledby="table-title" tabindex="0">
    <table aria-label="Contacts Directory">
        <caption id="table-title">Contacts Directory</caption>
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">First Name</th>
            <th scope="col">Last Name</th>
            <th scope="col">Email</th>
            <th scope="col">Phone</th>
            <th scope="col">Edit</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($contacts)): ?>
	        <?php foreach ($contacts as $contact): ?>
                <tr>
                    <td><?= htmlspecialchars($contact['id']) ?></td>
                    <td><?= htmlspecialchars($contact['first_name']) ?></td>
                    <td><?= htmlspecialchars($contact['last_name']) ?></td>
                    <td><?= htmlspecialchars($contact['email']) ?></td>
                    <td><?= htmlspecialchars('+ ' . $contact['country_code'] . ' ' . $contact['phone']) ?></td>
                    <td><a href="/edit_contact.php?id=<?= urlencode($contact['id']) ?>" role="button">Edit</a></td>
                </tr>
	        <?php endforeach; ?>
        <?php else: ?>
            <!-- colspan makes it take up the width of 5 columns -->
            <tr><td colspan="5">No users found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>