<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./styles/main.css">
    <title>Inventory App</title>
</head>
<body>
    <header class="header">
        <h1 class="header-title">inventory app</h1>
    </header>

    <section class="form-section">
        <form action="./php/audit.php" method="post">
        
            <div class="location-form">
                <label class="label" for="Location"></label>
                <select id="Location" name="Location" class="select-input location">
                    <option value="henry">henry</option>
                    <option value="oak-1">oak-1</option>
                    <option value="oak-2">oak-2</option>
                </select>
            </div>  

            <div class="table-container">
                <table class="table">
                    <?php include_once './php/items.php'; ?>
                </table>
                <div class="button-container">
                    <button type="submit" class="form-button primary-button">submit</button>
                    <a href="./php/tableDisplay.php" class="form-button secondary-button">supply</a>
                </div>
            </div>
        </form>
    </section>
</body>
</html>
