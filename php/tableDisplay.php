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
            <th>Last Quantity</th>
            <th>Last Date</th>
            <th>New Quantity</th>
            <th>New Date</th>
            <th>Draw/Wk</th>
            <th>Date</th>
        </tr>
        <?php
        if ($rateResult->num_rows > 0) {
            while ($row = $rateResult->fetch_assoc()) {
                // Debug statement to print the array
                // print_r($row);
                echo "<tr>";
                echo "<td>" . $row["Location"] . "</td>";
                echo "<td>" . $row["Item"] . "</td>";
                echo "<td>" . $row["Old_Quantity"] . "</td>";
                echo "<td>" . $row["Old_Date"] . "</td>";
                echo "<td>" . $row["New_Quantity"] . "</td>";
                echo "<td>" . $row["New_Date"] . "</td>";
                echo "<td>" . $row["Draw_Rate_Per_Wk"] . "</td>";
                echo "<td>" . $row["Created_At"] . "</td>";
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
        <th>LCL</th>
        <th>Date</th>
        <th>Notes</th>
    </tr>
    <?php
    // Modify your SQL query to include ORDER BY
    $sql = "SELECT Location, Item, Quantity, LCL, Date, Notes FROM Supply ORDER BY CAST(SUBSTRING(Item, 1, 2) AS UNSIGNED), Item"; // ASC for ascending order, DESC for descending order
    // Execute the SQL query
    $SupplyResult = $conn->query($sql);

    if ($SupplyResult->num_rows > 0) {
        while ($row = $SupplyResult->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["Location"] . "</td>";
            echo "<td>" . $row["Item"] . "</td>";
            echo "<td>" . $row["Quantity"] . "</td>";
            echo "<td style='background-color: " . (($row["Quantity"] < $row["LCL"]) ? "yellow" : "white") . "'>" . $row["LCL"] . "</td>";
            echo "<td>" . $row["Date"] . "</td>";
            echo "<td>" . $row["Notes"] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No data available</td></tr>";
    }
    ?>
</table>

    <?php
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>
