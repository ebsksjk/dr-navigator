<link rel="stylesheet" href="styles/table.css">
<title>dr-navigator - Route <?php echo $_GET['RID']; ?></title>
<table class="result-table">
    <?php
        require('DBConnect.php');
        foreach($DBASE->query("SELECT ID, Name FROM Routes WHERE ID='".$_GET['RID']."';") as $row) {
            echo '<tr><th class="title">Strecke '.$row['ID'].'</th><th class="title">'.$row['Name'].'</th></tr>';
        }

        foreach($DBASE->query("SELECT W.SR100, S.Name, W.KM FROM Waypoints AS W 
            INNER JOIN Routes AS R ON W.ROID=R.ID 
            INNER JOIN Stations AS S ON W.SR100=S.R100 
            WHERE ROID=".$_GET['RID'].";") as $row) {
            
            echo '<tr><td><a href="viewStation.php?SR100='.$row['SR100'].'">'.$row['Name'].'</a></td><td>KM '.$row['KM'].'</td></tr>';
        }
    ?>
</table>

<table class="more-routes">
    <?php
        foreach($DBASE->query("SELECT ID, Name FROM Routes;") as $row) {
            echo '<td><a href="viewRoute.php?RID='.$row['ID'].'">'.$row['Name'].'</a></td>';
        }
    ?>
</table>