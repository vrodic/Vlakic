<?php
        if (!pg_pconnect("dbname=vlakic user=vlakic")) die ("Db connection failed");
	//pg_set_client_encoding ("LATIN2");
	pg_set_client_encoding ("UTF8");
?>
