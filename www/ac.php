<?
if (empty($lang)) {
	$lang = "Cro";
}
function spittable($query) {
$res = pg_query($query);
$num = pg_num_rows($res);
$cols = pg_num_fields($res);

for ($i = 0; $i < $num;$i++){
	$c = "";
	for($j = 0; $j < $cols; $j++) {
		$c .= pg_result($res,$i,$j)."|";
	}

	echo "$c\n";
}

echo "\n";
exit();

}
require("include/dbcon.inc");
$q = $_GET['q'];
$qw = strtoupper($q);


$place = $_GET['place'];
if ($place) {
$query = "SELECT Name,id FROM Stations where lower(name) LIKE lower('$q%')";
spittable($query);

}


?>