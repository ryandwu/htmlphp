<!DOCTYPE html>
<html>
<head>
    <title>Contact Us</title>
</head>
<body>
    <h1>Contact Us</h1>
    
    <?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

    // Check if the form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if the expected form fields are set in the $_POST array
        if (isset($_POST["name"]) && isset($_POST["email"]) && isset($_POST["message"])) {
            // Retrieve form data
            $name = $_POST["name"];
            $email = $_POST["email"];
            $message = $_POST["message"];

            // Create a database connection
            $servername = "localhost";  // Replace with your MySQL server name or IP address
            $username = "ryandwu";         // Replace with your MySQL username
            $password = "Databasepwd";     // Replace with your MySQL password
            $database = "testdatabase";   // Replace with your MySQL database name

            $conn = new mysqli($servername, $username, $password, $database);

            // Check the connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Prepare and execute an SQL INSERT statement
            $sql = "INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $name, $email, $message);

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

    <form action="" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name"><br><br>

        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email"><br><br>

        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="4" cols="50"></textarea><br><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
