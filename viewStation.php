<link rel="stylesheet" href="styles/table.css">
<title>dr-navigator - Fahrplan</title>

<?php
    $station = $_GET["SR100"];

    require('DBConnect.php');

    if ($station == NULL) {
        echo '<h1 class="title">Dieser Bahnhof existiert leider nicht.</h1>';
        return;
    }

    foreach($DBASE->query('SELECT Name FROM Stations WHERE R100="'.$station.'";') as $row) {
        echo '<h1 class="title">Fahrpläne '.$row["Name"].'</h1>';
    }

    echo '<table class="result-table">';
    echo '<tr><th>Zugnummer</th><th>Von</th><th>Ankunft um</th><th>Abfahrt um</th><th>Nach</th></tr>';
    
    foreach($DBASE->query('
    SELECT T.Von AS VR100, T.Nach AS NR100, s2.Name AS Von, St.Arrival AS Ankunft, T.ID AS Zugnummer, St.Departure AS Abfahrt, s3.Name AS Nach 
    FROM Stops as St
    INNER JOIN Trains as T on St.TID=T.ID 
    INNER JOIN Stations as s on s.R100=St.SR100 
    INNER JOIN Stations as s2 on s2.R100=T.Von
    INNER JOIN Stations as s3 on s3.R100=T.Nach 
    WHERE ST.SR100="'.$station.'";') as $row)  {
        echo '<tr>
            <td><a href="viewTrain.php?TID='.$row['Zugnummer'].'">'.$row['Zugnummer'].'</a></td>
            <td><a href="viewStation.php?SR100='.$row['VR100'].'">'.$row['Von'].'</a></td>';
            if ($row['Ankunft'] == "START") {
                echo '<td>---</td>';
             }else{
                echo'<td>'.$row['Ankunft'].' Uhr</td>';
             }
            if ($row['Abfahrt'] == "END") {
                echo '<td>---</td>';
             }else{
                echo'<td>'.$row['Abfahrt'].' Uhr</td>';
             }
            echo '<td><a href="viewStation.php?SR100='.$row['NR100'].'">'.$row['Nach'].'</a></td>
              </tr>';
    }

    echo '</table>';

?>