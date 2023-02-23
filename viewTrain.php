<link rel="stylesheet" href="styles/table.css">
<title>dr-navigator - Zuginformationen</title>

<?php
    $trainID = $_GET['TID'];

    require('tools/DBConnect.php');

    if($trainID == NULL) {
        echo '<h1> Dieser Zug wurde nicht gefunden. </h1>';
        return;
    }

    foreach($DBASE->query('
    SELECT T.ID, s1.Name AS Von, s2.Name AS Nach
    FROM Trains AS T
    INNER JOIN Stations AS s1 ON T.Von=s1.R100
    INNER JOIN Stations AS s2 ON T.Nach=s2.R100
    WHERE ID="'.$trainID.'"') as $row)  {
        echo '<h1 class="title">Zug '.$row['ID'].' von '.$row['Von'].' nach '.$row['Nach'].'</h1>';
    }

    echo '<table class="result-table">';
        echo '<tr><th>Station</th><th>Ankunft</th><th>Abfahrt</th></tr>';

        foreach($DBASE->query('
        SELECT st.SR100, s.Name, St.Arrival, St.Departure
        FROM Stops AS St
        INNER JOIN Stations AS s ON s.R100=St.SR100
        WHERE St.TID="'.$trainID.'"') as $row) {
            echo '<tr>
                <td><a href="viewStation.php?SR100='.$row['SR100'].'">'.$row['Name'].'</a></td>';
                if ($row['Arrival'] == "START") {
                    echo '<td>---</td>';
                 }else{
                    echo'<td>'.$row['Arrival'].' Uhr</td>';
                 }
                if ($row['Departure'] == "END") {
                    echo '<td>---</td>';
                 }else{
                    echo'<td>'.$row['Departure'].' Uhr</td>';
                 }
            echo '</tr>';
        }
    echo '</table>';
?>