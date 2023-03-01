<?php
    require('DBConnect.php');

    foreach($DBASE->query("SELECT R100 FROM Stations WHERE Name='".$_GET['station']."';") as $row) {
        echo $row['R100'];
    }
?>