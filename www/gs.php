<?
require("include/dbcon.inc");
require("include/common.inc");

$from = get_station_id_low(trim($_GET["from"]));
$to = get_station_id_low(trim($_GET["to"]));
$now = date("H:i");
$now = "12:00";
$day = date("D");
//$econd = "and going > '$now'";
echo $now."<br>";
    $sql = "SELECT distinct movableid,going,coming,fromstation, tostation, extratxt FROM StationMovables WHERE statid='$from' $econd order by going";
   // echo $sql;
    //$sql = "SELECT distinct movableid, coming, going, fromstation, tostation, extratxt  FROM StationMovables WHERE statid='$from'  order by going";
    $res = pg_query($sql);
    echo "<table cellspacing=4>";
    $num = pg_num_rows($res);
    for ($i = 0;$i < $num; $i++) {
        $movable = pg_result($res,$i,0);
        
        $going = pg_result($res,$i,1);
        $coming = pg_result($res,$i,2);
          
        $fromstation = pg_result($res,$i,3);
        $tostation = pg_result($res,$i,4);
        $extratxt = pg_result($res,$i,5);
        $condstart = 0;
        if ($going) {
            $condstr = "going > '$going' or coming > '$going'";
            $condstart = 1;
        }
        /*if ($coming) {
            if ($condstart) {
                $condstr = "$condstr or coming > '$coming'";
            } else {
                $condstr = "coming > '$coming'";
            }
        }*/
        $sql = "SELECT coming,going FROM StationMovables WHERE  movableid='$movable' AND statid='$to' and ($condstr) ";
        //echo $sql;
        $res2 = pg_query($sql);
        $num2 = pg_num_rows($res2);
        
        if ($num2) {
            $coming = pg_result($res2,0,0);
            if (! $coming) $coming = pg_result($res2,0,1);
           if ((strtotime($coming) - strtotime($going)) > 60*60*18) {
           } else {
           /* $sql = "SELECT  going, fromstation, tostation, extratxt  FROM StationMovables WHERE statid='$from' and movableid='$movable'  order by going";
           
           
        $res2 = pg_query($sql);
        while ($row = pg_fetch_assoc($res2)) {*/
        echo "<tr>";        
      echo "<td><a href='index.php?movable=".$movable."'>".$movable."</a></td>";
  echo "<td>".$going."</td>";
  echo "<td>".$coming."</td>";
  $fs = get_station_name($fromstation);
  $ts =  get_station_name($tostation);
  echo "<td>$fs</td><td>$ts</td><td>".$extratxt."</td>";
  echo "</tr>";
    
    }
        }
    }
    echo "</table>";
    
    exit();

    


?>
