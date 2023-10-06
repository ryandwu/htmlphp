<?php
$items = [
    "1-toilet-paper" => ["label" => "1-toilet-paper", "LCL" =>19],
    "2-paper-towel" => ["label" => "2-paper-towel", "LCL" =>5],
    "3-big-trash-bag" => ["label" => "3-big-trash-bag", "LCL" =>14],
    "4-small-trash-bag" => ["label" => "4-small-trash-bag", "LCL" =>10],
    "5-shampoo" => ["label" => "5-shampoo", "LCL" =>2],
    "6-body-wash" => ["label" => "6-body-wash", "LCL" =>2],
    "7-hand-soap" => ["label" => "7-hand-soap", "LCL" =>1],
    "8-dish-soap" => ["label" => "8-dish-soap", "LCL" =>1],
    "9-laundry-detergent" => ["label" => "9-laundry-detergent", "LCL" =>1],
    "10-dishwasher-pod" => ["label" => "10-dishwasher-pod", "LCL" =>28],
    "11-coffee-pod" => ["label" => "11-coffee-pod", "LCL" =>1],
    "12-creamer" => ["label" => "12-creamer", "LCL" =>28],
    "13-bath-towel" => ["label" => "13-bath-towel", "LCL" =>5],
    "14-hand-towel" => ["label" => "14-hand-towel", "LCL" =>4],
    "15-kleenex" => ["label" => "15-kleenex", "LCL" =>5],
    "16-throw-blanket" => ["label" => "16-throw-blanket", "LCL" =>2],
    "17-queen-comforter-costco" => ["label" => "17-queen-comforter-costco", "LCL" =>2],
    "18-queen-comforter-ikea-nocover" => ["label" => "18-queen-comforter-ikea-nocover", "LCL" =>0],
    "19-pillow-queen" => ["label" => "19-pillow-queen", "LCL" =>2],
    "20-sheets-queen" => ["label" => "20-sheets-queen", "LCL" =>2],
    "21-sheets-twin" => ["label" => "21-sheets-twin", "LCL" =>2],
    "22-sponge" => ["label" => "22-sponge", "LCL" =>5],
    "23-bowl" => ["label" => "23-bowl", "LCL" =>0],
    "24-plate" => ["label" => "24-plate", "LCL" =>0],
    "25-aluminum-foil" => ["label" => "25-aluminum-foil", "LCL" =>1],
    "26-baking-sheets" => ["label" => "26-baking-sheets", "LCL" =>0],
    "27-folk-set" => ["label" => "27-folk-set", "LCL" =>0],
    "28-glass" => ["label" => "28-glass", "LCL" =>0],
    "29-toilet-water-snake" => ["label" => "29-toilet-water-snake", "LCL" =>0],
    "30-pot-boiling-big" => ["label" => "30-pot-boiling-big", "LCL" =>0],
    "31-pot-boiling-stainless-handle" => ["label" => "31-pot-boiling-stainless-handle", "LCL" =>0],
    "32-fry-pan" => ["label" => "32-fry-pan", "LCL" =>0],
    "33-mitten" => ["label" => "33-mitten", "LCL" =>0],
    "34-ladle-spatula-set" => ["label" => "34-ladle-spatula-set", "LCL" =>0],
    "35-cutting-board" => ["label" => "35-cutting-board", "LCL" =>0],
    "36-knife-set" => ["label" => "36-knife-set", "LCL" =>0],
    "37-hair-dryer" => ["label" => "37-hair-dryer", "LCL" =>0],
    "38-table-lamp-big" => ["label" => "38-table-lamp-big", "LCL" =>0],
    "39-table-lamp-small" => ["label" => "39-table-lamp-small", "LCL" =>0],
    "40-plunger" => ["label" => "40-plunger", "LCL" =>0],
    "70-comforter-queen" => ["label" => "70-comforter-queen", "LCL" =>0],
    "71-sheets-queen" => ["label" => "71-sheets-queen", "LCL" =>0],
    "72-sheets-twin" => ["label" => "72-sheets-twin", "LCL" =>0],
    "73-toilet-paper" => ["label" => "73-toilet-paper", "LCL" =>0],
    "74-paper-towel" => ["label" => "74-paper-towel", "LCL" =>0],
];

?>

<table class="select-input item">
    <tr>
        <th>Item</th>
        <th>Quantity</th>
        <th>LCL</th>
        <th>Notes</th>
    </tr>
    <?php
    // Generate item rows
    for ($i = 1; $i <= 5; $i++) {
        echo "<tr>";

        echo "<td>";
        echo "<select id='Item$i' name='Item$i' class='form-input' onchange='updateLCL($i)'>";
        foreach ($items as $key => $itemData) {
            $selected = ($key == $Item) ? "selected" : "";
            echo "<option value='$key' data-lcl='{$itemData["LCL"]}' $selected>{$itemData["label"]}</option>";
        }
        echo "</select>";
        echo "</td>";

        echo "<td>";
        // echo "<label for='Quantity$i'>Quantity $i:</label>";
        echo "<select id='Quantity$i' name='Quantity$i' class='form-input'>";
        for ($j = 0; $j <= 200; $j++) {
            echo "<option value='$j'>$j</option>";
        }
        echo "</select>";
        echo "</td>";

        // Add a new column for "LCL" and populate it based on the selected item
        echo "<td>";
        echo "<select id='LCL$i' name='LCL$i' class='form-input'>";
        for ($j = 0; $j <= 200; $j++) {
            echo "<option value='$j'>$j</option>";
        }
        echo "</select>";
        echo "</td>";

        // Display the "Notes" input field with the MySQL "Notes" value
        echo "<td>";
        echo "<input type='text' id='Notes$i' name='Notes$i' class='form-input' value='{$itemDataArray[$i]["Notes"]}'>";
        echo "</td>";
    }
    ?>
</table>

<script>
function updateLCL(i) {
    const itemSelect = document.getElementById(`Item${i}`);
    const lclSelect = document.getElementById(`LCL${i}`); // Updated ID to match HTML
    const notesInput = document.getElementById(`Notes${i}`); // New Notes input

    // Get the selected item's data-lcl attribute
    const selectedLCL = itemSelect.options[itemSelect.selectedIndex].getAttribute('data-lcl');
    const selectedNotes = itemSelect.options[itemSelect.selectedIndex].getAttribute('data-notes');

    // Set the selected LCL in the LCL select element
    lclSelect.value = selectedLCL;
    notesInput.value = selectedNotes;
}
</script>