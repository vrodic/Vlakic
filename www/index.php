<html> 
<head> 
<title>Creditreform d.o.o.</title> 
  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"> 


    <script type="text/javascript" src="include/js/jsall.js"></script> 
<link rel="stylesheet" type="text/css" href="include/js/jquery.autocomplete.css" /> 
<script type="text/javascript"> 
 
 $(document).ready(function(){
    
    $('#to').keypress(function(e){
      if(e.which == 13){
         $.ajax({
   type: "GET",
   url: "gs.php",
   dataType: "html",
   data: "from="+$("#from").val()+"&to="+$("#to").val(),
   success: function(msg){

 if (msg == 'error') {
   
   
	} else {
    $("#content").html(msg);
	}


     //alert( "Data Saved: " + msg );


   }
 });
       }
      });
    
    
$("#from").autocomplete("ac.php?place=1", {
		width: 260,
		selectFirst: false,
		autoFill:true,
		mustmatch:true,
		formatItem: function(data, i, total) {
			// don't show the current month in the list of values (for whatever reason)
			
			return data[0];
		}
 
	}).result(function(event, item) {
            $("#fromid").val(item[1]);
	});

$("#to").autocomplete("ac.php?place=1", {
		width: 260,
		selectFirst: false,
		autoFill:true,
		mustmatch:true,
		formatItem: function(data, i, total) {
			// don't show the current month in the list of values (for whatever reason)
			
			return data[0];
		}
 
	}).result(function(event, item) {
            $("#toid").val(item[1]);
	});

 }); </script>
</head>
<body>
Od:<input type="text" id="from">
Do:<input type="text" id="to">
<input type="hidden" id="fromid">
<input type="hidden" id="toid">
<div id="content">

<?
require("include/dbcon.inc");
require("include/common.inc");

$statid = $_GET["station"];

$movable = $_GET["movable"];
if ($statid) {
    $stationname = get_station_name($statid);
    echo "<b>$stationname</b><br>";
    $sql = "SELECT movableid, coming, going, fromstation, tostation, extratxt  FROM StationMovables WHERE statid='$statid'";
    $res = pg_query($sql);
    echo "<table>";
    while ($row = pg_fetch_assoc($res)) {
        echo "<tr>";        
      echo "<td><a href='index.php?movable=".$row['movableid']."'>".$row['movableid']."</a></td>";
  echo "<td>".$row['coming']."</td>";
  echo "<td>".$row['going']."</td>";
  $fs = get_station_name($row['fromstation']);
  $ts =  get_station_name($row['tostation']);
  echo "<td>$fs</td><td>$ts</td><td>".$row['extratxt']."</td>";
  echo "</tr>";
    }
    echo "</table>";

} else if ($movable){
    $sql = "SELECT distinct coming,going, fromstation, tostation, extratxt, statid FROM StationMovables where movableid='$movable' order by going asc";
      $res = pg_query($sql);
    echo "<table>";
    while ($row = pg_fetch_assoc($res)) {
        echo "<tr>";
          $statid = $row['statid'];
        
        $stationname = get_station_name($statid);
      echo "<td><a href='index.php?station=".$row['statid']."'>".$stationname."</a></td>";
  echo "<td>".$row['coming']."</td>";
  echo "<td>".$row['going']."</td>";
  $fs = get_station_name($row['fromstation']);
  $ts =  get_station_name($row['tostation']);

  echo "<td>$fs</td><td>$ts</td><td>".$row['extratxt']."</td>";
  echo "</tr>";
    }
    echo "</table>";
    
} else {
$res = pg_query("SELECT * FROM Stations");
$num = pg_num_rows($res);
for ($i = 0; $i < $num;$i++) {
    $id = pg_result($res,$i,0);
    $name = pg_result($res,$i,1);
    echo "<a href='index.php?station=$id'>$name</a><br>";
}
}

?>
</div>
</body>
</html>
