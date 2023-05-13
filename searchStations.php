<?php

    //SELECT Name FROM Stations WHERE Name LIKE 

    require('DBConnect.php');

    //echo $_GET['query'] . " \n";

    foreach($DBASE->query("SELECT s.Name, s.R100 FROM Stations AS s WHERE s.Name LIKE '%".$_GET['query']."%';") as $row) {
        echo $row['Name'] . " - (" . $row['R100'] . ") \n";
    }

?>