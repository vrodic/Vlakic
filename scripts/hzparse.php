<?
require("include/dbcon.inc");
require("include/simple_html_dom.php");
require("include/common.inc");



function create_station($stationname) {
    $sql ="INSERT INTO Stations (name) VALUES('$stationname')";
    pg_query($sql);
    $sql = "SELECT id from Stations ORDER by id desc limit 1";
    $res = pg_query($sql);
    return pg_result($res,0,0);
}

function get_station($statid) {

//return;

$stationname = get_station_name($statid);
$stationnameorig = urlencode(recode("UTF8..windows-1250",$stationname));
$stationurl = "http://vred.hznet.hr/hzinfo/Default.asp?KO=$stationnameorig&Category=hzinfo&Service=izvr3&LANG=HR&SCREEN=2";
    
$html = file_get_dom($stationurl);

//$html = file_get_dom('http://vred.hznet.hr/hzinfo/Default.asp?KO=Zagreb+Gl.+Kol.&Category=hzinfo&Service=izvr3&LANG=HR&SCREEN=2&SESSIONID=%3Csessionid%3E');


//$statid = get_station_id($stationname);
//if ($statid == -1) return;

foreach($html->find('td') as $element) {
    $foundin = 0;
    foreach ($element->find("a") as $innerel) {
        //echo trim($innerel->innertext)."\n";
        // commit previous work here
        if ($vlaknr) {
            if ($vlakiz) {
            $vlakiz = get_station_id($vlakiz);
            } else {
                $vlakiz = 0;
            }
            if ($vlakza) {
            $vlakza = get_station_id($vlakza);
            } else {
                $vlakza = 0;
            }
            $vlakdolstr = "'$vlakdol'";
            if ($vlakdol == '') $vlakdolstr = "NULL";
            
            $vlakodlstr = "'$vlakodl'";
            if ($vlakodl == '') $vlakodlstr = "NULL";
            $sun = 1;
            $sat = 1;
            $vlaknaphandled =0;
            // blah, this is tiresome
            if ($vlaknap == "") {
                $vlaknaphandled =1;
            }
            if ($vlaknap == "Ne vozi nedjeljom i blagdanom") {
                $sun = 0;
                $vlaknaphandled =1;
            }
            
            if ($vlaknap == "Ne vozi subotom") {
                $sat = 0;
                $vlaknaphandled =1;
            }
            
            if ($vlaknap == "Vozi nedjeljom") {
                
                $vlaknaphandled =1;
            }
            
            if ($vlaknap == "Vozi nedjeljom i blagdanom") {
                
                $vlaknaphandled =1;
            }
            
            
            if ($vlaknap == "Vozi subotom, nedjeljom i blagdanom") {
                
                $vlaknaphandled =1;
            }
            
            if ($vlaknap == "Ne vozi subotom, nedjeljom i blagdanom") {
                $sat = 0;
                $sun = 0;
                $vlaknaphandled =1;
            }
            //$pos = strpos("Ne vozi subotom, nedjeljom i blagdanom", $vlaknap);
            //if ($pos > 0)
            

            $sql =  "INSERT INTO StationMovables values('$statid','$vlaknr',$vlakdolstr, $vlakodlstr, '$vlakiz' ,'$vlakza','$vlaknap','$sun','$sat','$vlaknaphandled');";
            echo $sql."\n";
            pg_query($sql);
           
        }
        $vlaknr = trim($innerel->innertext);
        $foundin = 1;
        $col = 0;
    }
    if (! $foundin) {
        $txt = trim($element->innertext);
        $txt = str_ireplace("<BR>", "", $txt);

        switch ($col) {
            case 0: $vlakdol = $txt;
                break;
            case 1: $vlakodl = $txt;
                break;
            case 6: $vlakiz = recode("windows1250..UTF-8",$txt);
                break;
            case 7: $vlakza = recode("windows1250..UTF-8",$txt);
                break;
            case 8: $vlaknap = recode("windows1250..UTF-8",$txt);
                break;             
        }
        $col++;
    }
}
  if ($vlaknr) {
            if ($vlakiz) {
            $vlakiz = get_station_id($vlakiz);
            } else {
                $vlakiz = 0;
            }
            if ($vlakza) {
            $vlakza = get_station_id($vlakza);
            } else {
                $vlakza = 0;
            }
            $vlakdolstr = "'$vlakdol'";
            if ($vlakdol == '') $vlakdolstr = "NULL";
            
            $vlakodlstr = "'$vlakodl'";
            if ($vlakodl == '') $vlakodlstr = "NULL";

            $sql =  "INSERT INTO StationMovables values('$statid','$vlaknr',$vlakdolstr, $vlakodlstr, '$vlakiz' ,'$vlakza','$vlaknap');";
            echo $sql."\n";
            pg_query($sql);
           
        }
    $html->clear;
    unset($html);
}

if ($argv[1] == "pop") {
$res = pg_query("SELECT distinct movableid FROM StationMovables ");
$num = pg_num_rows($res);
for ($i = 0; $i < $num;$i++) {
    $mov = pg_result($res,$i,0);
    $res2 = pg_query("SELECT  distinct going,coming,  fromstation, tostation, extratxt, statid FROM StationMovables where movableid='$mov' order by going");
    $num2 = pg_numrows($res2);
    $num2f = pg_numfields($res2);
    for ($j = 0;$j < $num2; $j++) {
        $insvals = "";
        for ($z= 0;$z < $num2f;$z++ ) {
            $insvals .="'".pg_result($res2,$j,$z)."',";
        }
        $insvals = substr($insvals,0,-1);
        $sql = "INSERT INTO MovableTimeTable VALUES ('$mov', $insvals)";
        echo $sql."\n";
        //pg_query($sql);
    }

}

exit;
}


if ($argv[1] == "get_station") {
    get_station($argv[2]);
    exit;
}

$html =  file_get_dom("http://vred.hznet.hr/hzinfo/?category=hzinfo&service=izvr3");

$htmls = explode("\n", $html);
echo sizeof($htmls);

foreach($htmls as $line) {
    $line = trim($line);
    //echo "$line\n";
    if (strtoupper(substr($line,0,8)) == "<OPTION>") {
        $line = trim(substr($line,8));


    $stationname = trim(recode("windows-1250..UTF-8",$line));
    echo $stationname."\n";
    $statid = get_station_id($stationname);
    if ($statid == -1) {
        $statid =create_station($stationname);
    }
   // get_station($statid,$stationurl);
    }
}

foreach($htmls as $line) {
    $line = trim($line);
    //echo "$line\n";
    if (strtoupper(substr($line,0,8)) == "<OPTION>") {
        $line = trim(substr($line,8));
     
        
    $stationname = trim(recode("windows-1250..UTF-8",$line));
    echo $stationname."\n";
    $statid = get_station_id($stationname);
    system ("php5 hzparse.php get_station $statid");
    }
}




    
?>
