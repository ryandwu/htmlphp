<!-- submit_contact.php (Back End) -->
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $message = $_POST["message"];

    try {
        // TODO: Save the contact information to the database or perform desired actions.
        // For example, you can use PDO for database interactions.
        
        // Replace the following lines with your database logic.
        $pdo = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');
        // Your database operations here.

        // Redirect back to the contact form with a success message.
        header("Location: contact.html?success=1");
        exit();
    } catch (PDOException $e) {
        // Handle database connection errors or other exceptions.
        // Log the error message and redirect with an error message.
        error_log("Database Error: " . $e->getMessage(), 0);
        header("Location: contact.html?error=1");
        exit();
    } catch (Exception $e) {
        // Handle other exceptions if needed.
        // Log the error message and redirect with an error message.
        error_log("Error: " . $e->getMessage(), 0);
        header("Location: contact.html?error=1");
        exit();
    }
}
?>