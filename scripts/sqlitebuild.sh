rm data/vlakic.db
sqlite3 data/vlakic.db < db.sqlite
php5 tosqlite.php > data/vlakic.sql
sqlite3 data/vlakic.db < data/vlakic.sql
