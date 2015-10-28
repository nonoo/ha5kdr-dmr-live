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

	$conn = mysqli_connect(DMR_LIVE_DB_HOST, DMR_LIVE_DB_USER, DMR_LIVE_DB_PASSWORD, DMR_LIVE_DB_NAME);
	if (!$conn) {
		echo "can't connect to mysql database!\n";
		return 1;
	}

	$conn->query("set names 'utf8'");
	$conn->query("set charset 'utf8'");

	// Deleting old entries.
	$conn->query('delete from `' . DMR_LIVE_DB_TABLE . '` where unix_timestamp(`date`) < (UNIX_TIMESTAMP() - ' . DMR_LIVE_DB_CLEAR_OLDER_THAN_SECONDS . ')');

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

		$conn->query('replace into `' . DMR_LIVE_DB_TABLE . '` ' .
			'(`date`, `callsign`, `repeater`, `repeaterid`, `callsignid`, ' .
			'`timeslot`, `group`, `name`, `dtmf`, `city`, `country`) values (' .
			'from_unixtime(' . $date . '), ' .
			'"' . $conn->escape_string($callsign) . '", ' .
			'"' . $conn->escape_string($repeater) . '", ' .
			'"' . $conn->escape_string($repeaterid) . '", ' .
			'"' . $conn->escape_string($callsignid) . '", ' .
			'"' . $conn->escape_string($timeslot) . '", ' .
			'"' . $conn->escape_string($group) . '", ' .
			'"' . $conn->escape_string($name) . '", ' .
			'"' . $conn->escape_string($dtmf) . '", ' .
			'"' . $conn->escape_string($city) . '", ' .
			'"' . $conn->escape_string($country) . '")');
	}

	$conn->close();
?>
