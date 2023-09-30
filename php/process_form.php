<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the expected form fields are set in the $_POST array
    if (isset($_POST["name"]) && isset($_POST["phone"]) && isset($_POST["email"]) && isset($_POST["message"])) {
        // Retrieve form data
        $name = $_POST["name"];
        $phone = $_POST["phone"];
        $email = $_POST["email"];
        $message = $_POST["message"];
        
        // Create a timestamp for the 'created_at' field
        $created_at = date("Y-m-d H:i:s");

        // Create a database connection
        $servername = "localhost";  // Replace with your MySQL server name or IP address
        $username = "ryan";         // Replace with your MySQL username
        $password = "Databasepwd";  // Replace with your MySQL password
        $database = "cashdealData"; // Replace with your MySQL database name

        $conn = new mysqli($servername, $username, $password, $database);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and execute an SQL INSERT statement
        $sql = "INSERT INTO contacts (name, phone, email, message, created_at) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $name, $phone, $email, $message, $created_at);

        if ($stmt->execute()) {
            echo "<p>Thank you for your submission!</p>";
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }

        // Close the database connection
        $stmt->close();
        $conn->close();
    } else {
        // Display an error message if the expected form fields are not set
        echo "<p>Error: Form fields are missing.</p>";
    }
}
?>
