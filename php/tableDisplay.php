<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../styles/main.css"> <!-- Link to your external CSS file -->
    <title>Inventory</title>
</head>
<body>
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Create a database connection
    $servername = "localhost";  // Replace with your MySQL server name or IP address
    $username = "ryan";         // Replace with your MySQL username
    $password = "Databasepwd";  // Replace with your MySQL password
    $database = "inventoryData"; // Replace with your MySQL database name

    $conn = new mysqli($servername, $username, $password, $database);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch data from the Supply table
    $SupplySql = "SELECT * FROM Supply";
    $SupplyResult = $conn->query($SupplySql);

    // Convert GMT to PST
    $timestamp7HoursAgo = date('Y-m-d H:i:s', strtotime('-7 hours'));
    // Calculate the timestamp for 96 hours ago
    $timestamp96HoursAgo = date('Y-m-d H:i:s', strtotime('-96 hours'));

    // Fetch data from the Rate table with sorting by Created_AT in descending order and limiting to 6 rows within the past 96 hours
    $rateSql = "SELECT * FROM Rate WHERE Created_AT >= '$timestamp96HoursAgo' ORDER BY Created_AT DESC LIMIT 100";

    $rateResult = $conn->query($rateSql);
    
    ?>

    

    <!-- Display the fetched data from Rate table as an HTML table -->
    <h2>Draw Rate</h2>
    <table class="table">
        <tr>
            <th>Location</th>
            <th>Item</th>
            <th>Old_Quantity</th>
            <th>Old_Date</th>
            <th>New_Quantity</th>
            <th>New_Date</th>
            <th>Draw_Rate_Per_Wk</th>
            <th>Created_AT</th>
        </tr>
        <?php
        if ($rateResult->num_rows > 0) {
            while ($row = $rateResult->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["Location"] . "</td>";
                echo "<td>" . $row["Item"] . "</td>";
                echo "<td>" . $row["Old_Quantity"] . "</td>";
                echo "<td>" . $row["Old_Date"] . "</td>";
                echo "<td>" . $row["New_Quantity"] . "</td>";
                echo "<td>" . $row["New_Date"] . "</td>";
                echo "<td>" . $row["Draw_Rate_Per_Wk"] . "</td>";
                echo "<td>" . $row["Created_AT"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No data available</td></tr>";
        }
        ?>
    </table>

    <!-- Display the fetched data from Supply table as an HTML table -->
    <h2>Inventory Table</h2>
<table class="table">
    <tr>
        <th>Location</th>
        <th>Item</th>
        <th>Quantity</th>
        <th>Low</th>
        <th>Date</th>
    </tr>
    <?php
    // Modify your SQL query to include ORDER BY
    $sql = "SELECT * FROM Supply ORDER BY Item ASC"; // ASC for ascending order, DESC for descending order

    // Execute the SQL query
    $SupplyResult = $conn->query($sql);

    if ($SupplyResult->num_rows > 0) {
        while ($row = $SupplyResult->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["Location"] . "</td>";
            echo "<td>" . $row["Item"] . "</td>";
            echo "<td>" . $row["Quantity"] . "</td>";
            echo "<td>" . $row["Low"] . "</td>";
            echo "<td>" . $row["Date"] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No data available</td></tr>";
    }
    ?>
</table>

    <!-- Rest of your HTML content here -->

    <?php
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>
