<?php
// Define the items array
$items = [
    "1-toilet-paper" => ["label" => "1-toilet-paper", "Low" => 10],
    "2-paper-towel" => ["label" => "2-paper-towel", "Low" => 5],
    "3-big-trash-bag" => ["label" => "3-big-trash-bag", "Low" => 50],
    "4-medium-trash-bag" => ["label" => "4-big-trash-bag", "Low" => 50],
    "5-large-trash-bag" => ["label" => "5-big-trash-bag", "Low" => 50],
];
?>

<table class="select-input item">
    <tr>
        <th>item</th>
        <th>qt</th>
        <th>lCL</th>
    </tr>
    <?php
    // Generate item rows
    for ($i = 1; $i <= 5; $i++) {
        echo "<tr>";
        echo "<td>";
        // echo "<label for='Item$i'>Item $i:</label>";
        echo "<select id='Item$i' name='Item$i' class='select-input item'>";
        foreach ($items as $key => $item) {
            echo "<option value='$key' data-low='{$item['Low']}'>{$item['label']}</option>";
        }
        echo "</select>";
        echo "</td>";
        echo "<td>";
        // echo "<label for='Quantity$i'>Quantity $i:</label>";
        echo "<select id='Quantity$i' name='Quantity$i' class='select-input quantity'>";
        for ($j = 0; $j <= 200; $j++) {
            echo "<option value='$j'>$j</option>";
        }
        echo "</select>";
        echo "</td>";

        // Add a new column for "Low" and populate it based on the selected item
        echo "<td>";
        echo "<select id='LCL$i' name='LCL$i' class='select-input lcl'>";
        for ($j = 0; $j <= 200; $j++) {
            echo "<option value='$j'>$j</option>";
        }
        echo "<span id='Low$i'></span>"; // This span will be used to display the "Low" value
        echo "</td>";
        
        echo "</tr>";
    }
    ?>
</table>
