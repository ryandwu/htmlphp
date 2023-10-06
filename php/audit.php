<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to establish a database connection
function connectToDatabase() {
    $servername = "localhost";
    $username = "ryan";
    $password = "Databasepwd";
    $database = "inventoryData";

    // Create a new mysqli connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check for connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // Set the MySQL session timezone to Los Angeles (Pacific Time)
    $conn->query("SET time_zone = '-07:00'");

    return $conn;
}

// Function to fetch old data (Quantity and Date) from the Supply table
function fetchOldData($conn, $Location, $Item) {
    $fetchOldDataSql = "SELECT Quantity, Date FROM Supply WHERE Location = ? AND Item = ?";
    $fetchOldDataStmt = $conn->prepare($fetchOldDataSql);
    $fetchOldDataStmt->bind_param("ss", $Location, $Item);
    $fetchOldDataStmt->execute();
    $fetchOldDataResult = $fetchOldDataStmt->get_result();

    if ($fetchOldDataResult->num_rows > 0) {
        $row = $fetchOldDataResult->fetch_assoc();
        return [
            "oldQuantity" => $row["Quantity"],
            "oldDate" => $row["Date"]
        ];
    }

    return null;
}

// Function to fetch item data from the database
function fetchItemDataFromDatabase($conn) {
    $itemDataArray = array();

    $fetchItemDataSql = "SELECT Item, LCL, Notes FROM Supply";
    $fetchItemDataResult = $conn->query($fetchItemDataSql);

    if ($fetchItemDataResult->num_rows > 0) {
        while ($row = $fetchItemDataResult->fetch_assoc()) {
            $itemDataArray[] = array(
                "Item" => $row["Item"],
                "LCL" => $row["LCL"],
                "Notes" => $row["Notes"]
            );
        }
    }

    return $itemDataArray;
}

// Function to insert data into the Rate table
function insertDataIntoRateTable($conn, $Location, $Item, $oldQuantity, $oldDate, $Quantity, $created_at) {
    $drawRatePerWk = ($oldQuantity - $Quantity) / (strtotime($created_at) - strtotime($oldDate)) * 3600 * 24 * 7;
    
    // Format the created_at date
    $created_at_utc = new DateTime($created_at, new DateTimeZone('UTC'));
    $created_at_local = $created_at_utc->modify('-7 hours')->format('Y-m-d H:i:s');
    
    $insertRateSql = "INSERT INTO Rate (Location, Item, Old_Quantity, Old_Date, New_Quantity, New_Date, Draw_Rate_Per_Wk, Created_AT)
        VALUES (?, ?, ?, ?, ?, ?, ?, ? )";
    
    $insertRateStmt = $conn->prepare($insertRateSql);
    $insertRateStmt->bind_param("ssssssss", $Location, $Item, $oldQuantity, $oldDate, $Quantity, $created_at_local, $drawRatePerWk, $created_at_local);

    if ($insertRateStmt->execute()) {
        return true;
    } else {
        echo "Error: " . $insertRateStmt->error;
        return false;
    }
}

// Function to update data in the Supply table
function updateQuantityInSupplyTable($conn, $Location, $Item, $Quantity, $LCL, $created_at, $Notes) {
    // Convert the $created_at timestamp to the desired time zone ('-07:00' for Los Angeles)
    $created_at_local = new DateTime($created_at, new DateTimeZone('UTC'));
    $created_at_local->setTimezone(new DateTimeZone('America/Los_Angeles'));
    $formattedDate = $created_at_local->format('Y-m-d H:i:s');

    $updateQuantitySql = "UPDATE Supply SET Quantity = ?, LCL = ?, Date = ?, created_at = ?, `Notes` = ? WHERE Location = ? AND Item = ?";
    $updateQuantityStmt = $conn->prepare($updateQuantitySql);
    $updateQuantityStmt->bind_param("sssssss", $Quantity, $LCL, $formattedDate, $formattedDate, $Notes, $Location, $Item);

    return $updateQuantityStmt->execute();
}

// Function to insert data into the Supply table
function insertDataIntoSupplyTable($conn, $Location, $Item, $Quantity, $LCL, $created_at, $Notes) {
    // Convert the $created_at timestamp to the desired time zone ('-07:00' for Los Angeles)
    $created_at_local = new DateTime($created_at, new DateTimeZone('UTC'));
    $created_at_local->setTimezone(new DateTimeZone('America/Los_Angeles'));
    $formattedDate = $created_at_local->format('Y-m-d H:i:s');

    $insertSupplySql = "INSERT INTO Supply (Location, Item, Quantity, LCL, Date, created_at, Notes)
    VALUES (?, ?, ?, ?, ?, NOW(), ?)";
    
    $insertSupplyStmt = $conn->prepare($insertSupplySql);
    $insertSupplyStmt->bind_param("ssssss", $Location, $Item, $Quantity, $LCL, $formattedDate, $Notes);

    return $insertSupplyStmt->execute();
}

// Check if the request method is POST and Location is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Location"])) {
    $Location = $_POST["Location"];
    $conn = connectToDatabase();

    // Fetch item data from the database
    $itemDataArray = fetchItemDataFromDatabase($conn);

    for ($i = 1; $i <= 5; $i++) {
        $itemKey = "Item$i";
        $quantityKey = "Quantity$i";
        $LCLKey = "LCL$i";
        $NotesKey = "Notes$i";

        if (isset($_POST[$itemKey]) && isset($_POST[$quantityKey])) {
            $Item = strtolower($_POST[$itemKey]);
            $Quantity = $_POST[$quantityKey];
            $LCL = $_POST[$LCLKey];
            $created_at = date("Y-m-d H:i:s");
            $Notes = $_POST[$NotesKey];

            if ($oldData = fetchOldData($conn, $Location, $Item)) {
                $oldQuantity = $oldData["oldQuantity"];

                if (($oldQuantity - $Quantity) <= 0) {
                    if (updateQuantityInSupplyTable($conn, $Location, $Item, $Quantity, $LCL, $created_at, $Notes)) {
                        echo '<script>window.parent.location.href = "./tableDisplay.php";</script>';
                    } else {
                        echo "<p>Error updating Quantity in Supply table: " . $conn->error . "</p>";
                    }
                } else {
                    if (insertDataIntoRateTable($conn, $Location, $Item, $oldQuantity, $oldData["oldDate"], $Quantity, $created_at)) {
                        echo "Data inserted into Rate table.";

                        if (updateQuantityInSupplyTable($conn, $Location, $Item, $Quantity, $LCL, $created_at, $Notes)) {
                            echo '<script>window.parent.location.href = "./tableDisplay.php";</script>';
                        } else {
                            echo "<p>Error updating Quantity in Supply table: " . $conn->error . "</p>";
                        }
                    } else {
                        echo "<p>Error inserting data into Rate table: " . $conn->error . "</p>";
                    }
                }
            } else {
                if (insertDataIntoSupplyTable($conn, $Location, $Item, $Quantity, $LCL, $created_at, $Notes)) {
                    echo '<script>window.parent.location.href = "./tableDisplay.php";</script>';
                } else {
                    echo "<p>Error inserting data into Supply table: " . $conn->error . "</p>";
                }
            }
        }
    }

    $conn->close();
} else {
    echo "<p>Error: Form fields are missing.</p>";
}
?>