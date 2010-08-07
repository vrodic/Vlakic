<?
require("include/dbcon.inc");
require("include/common.inc");
 
  echo "BEGIN TRANSACTION;\n"; // this is what mozilla appearently doesn't know about :)
  
  function justdump ($tablename) {
 
 $res2 = pg_query("SELECT  * FROM $tablename ");
    $num2 = pg_numrows($res2);
 $num2f = pg_numfields($res2);
    for ($j = 0;$j < $num2; $j++) {
        $insvals = "";
        for ($z= 0;$z < $num2f;$z++ ) {
            $insvals .="'".pg_result($res2,$j,$z)."',";
        }
        $insvals = substr($insvals,0,-1);
        $sql = "INSERT INTO $tablename VALUES ($insvals);";
        echo $sql."\n";       
    }   
  }
 
    justdump("Stations");
    justdump("StationMovables");
    
 echo "COMMIT;\n";
?>