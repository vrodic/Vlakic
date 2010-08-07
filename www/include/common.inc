<?
function get_station_name($id) {
    $res2 = pg_query("SELECT name FROM Stations WHERE id='$id'");
  return pg_result($res2,0,0);
}

function get_station_id($stationname) {
    $psql = "SELECT id FROM Stations WHERE name='$stationname'";
    $res = pg_query($psql);
    $num = pg_num_rows($res);
if ($num) {
    $id = pg_result($res,0,0);
    return $id;
} else {
    echo "Station $stationname not found\n";
    return -1;
}
}

function get_station_id_low($stationname) {
    $stationname = strtolower($stationname);
    $psql = "SELECT id FROM Stations WHERE lower(name)='$stationname'";
    $res = pg_query($psql);
    $num = pg_num_rows($res);
if ($num) {
    $id = pg_result($res,0,0);
    return $id;
} else {
    echo "Station $stationname not found\n";
    return -1;
}
}


?>