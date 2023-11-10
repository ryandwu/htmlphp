<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="main.css">
    <title>InventoryApp</title>
</head>
<body>

    <!-- Header Section -->
    <section class="header">
            <div><img src="./public/logo.svg"></img></div>
    </section>

    
        <form action="./php/audit.php" method="post">
        <section class="table-container">
            <div class="primary-select">
                <select id="Location" name="Location" class="primary-select-location">
                    <option value="henry">henry</option>
                    <option value="oak-1">oak-1</option>
                    <option value="oak-2">oak-2</option>
                    <option value="oak-2">hancock</option>
                </select>


            </div>  
            </section>

            <div class="table-container">
                <table id="itemTable" class="table">
                    <?php include_once './php/items.php'; ?>
                </table>
                <div class="button-container">
                    <button type="submit" class="button">submit</button>
                    <a href="./php/tableDisplay.php" class="button">supply</a>
                </div>
            </div>
        </form>

</body>



</html>
