<link rel="stylesheet" href="table.css">
<title>dr-navigator - Stationen</title>

<table class="result-table">
    <?php

        require('DBConnect.php');
        echo '<tr><th class="title">R100</th><th class="title">Stationsname</th></tr>';

        foreach($DBASE->query("SELECT * FROM Stations;") as $row) {
            echo '<tr><td>'.$row['R100'].'</td><td>'.$row['Name'].'</td></tr>';
        }
    ?>
</table>