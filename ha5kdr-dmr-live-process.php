#!/usr/bin/php5
<?php
	ini_set('display_errors','On');
	error_reporting(E_ALL);

	include('ha5kdr-dmr-live-config.inc.php');

	$dmrdata = file_get_contents(DMR_LIVE_DATA_URL);
	if ($dmrdata === FALSE) {
		echo "error downloading live data file!\n";
		return 1;
	}

	$conn = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	if (!$conn) {
		echo "can't connect to mysql database!\n";
		return 1;
	}

	$db = mysql_select_db(DB_NAME, $conn);
	if (!$db) {
		mysql_close($conn);
		echo "can't connect to mysql database!\n";
		return 1;
	}

	mysql_query("set names 'utf8'");
	mysql_query("set charset 'utf8'");

	// Deleting old entries.
	mysql_query('delete from `' . DB_TABLE . '` where unix_timestamp(`date`) < (UNIX_TIMESTAMP() - ' . DB_CLEAR_OLDER_THAN_SECONDS . ')');

	$rows = explode("\n", $dmrdata);

	foreach ($rows as $row) {
		$row = str_replace('_', ' ', $row);

		$date = strtotime(substr($row, 0, 14));
		if ($date === FALSE)
			continue;
		$callsign = trim(substr($row, 29, 8));
		$repeater = trim(substr($row, 37, 8));
		$repeaterid = trim(substr($row, 45, 9));
		$callsignid = trim(substr($row, 54, 8));
		$timeslot = ltrim(trim(substr($row, 62, 10)), '0');
		$group = trim(substr($row, 74, 10));
		$name = trim(substr($row, 96, 20));
		$dtmf = trim(str_replace('.', '', substr($row, 116, 6)));
		$city = trim(substr($row, 122, 20));
		$country = trim(substr($row, 142));

		mysql_query('replace into `' . DB_TABLE . '` ' .
			'(`date`, `callsign`, `repeater`, `repeaterid`, `callsignid`, ' .
			'`timeslot`, `group`, `name`, `dtmf`, `city`, `country`) values (' .
			'from_unixtime(' . $date . '), ' .
			'"' . mysql_real_escape_string($callsign) . '", ' .
			'"' . mysql_real_escape_string($repeater) . '", ' .
			'"' . mysql_real_escape_string($repeaterid) . '", ' .
			'"' . mysql_real_escape_string($callsignid) . '", ' .
			'"' . mysql_real_escape_string($timeslot) . '", ' .
			'"' . mysql_real_escape_string($group) . '", ' .
			'"' . mysql_real_escape_string($name) . '", ' .
			'"' . mysql_real_escape_string($dtmf) . '", ' .
			'"' . mysql_real_escape_string($city) . '", ' .
			'"' . mysql_real_escape_string($country) . '")');
	}

	mysql_close($conn);
?>
