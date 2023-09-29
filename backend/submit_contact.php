<!-- submit_contact.php (Back End) -->
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $message = $_POST["message"];

    // TODO: Save the contact information to the database or perform desired actions.

    // Redirect back to the contact form with a success message.
    header("Location: contact.html?success=1");
    exit();
