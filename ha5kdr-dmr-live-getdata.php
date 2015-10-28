<?php
//	ini_set('display_errors','On');
//	error_reporting(E_ALL);

	function sanitize($s) {
		return strip_tags(stripslashes(trim($s)));
	}

	include('ha5kdr-dmr-live-config.inc.php');

	$conn = mysqli_connect(DMR_LIVE_DB_HOST, DMR_LIVE_DB_USER, DMR_LIVE_DB_PASSWORD, DMR_LIVE_DB_NAME);
	if (!$conn) {
		echo "can't connect to mysql database!\n";
		return;
	}

	$conn->query("set names 'utf8'");
	$conn->query("set charset 'utf8'");

	$searchfor = sanitize($_POST['searchfor']);
	$searchtoks = explode(' ', $searchfor);
	$search = '';
	for ($i = 0; $i < count($searchtoks); $i++) {
		if ($i == 0)
			$search = 'where ';
		else
			$search .= 'and ';

		$searchtok = $conn->escape_string($searchtoks[$i]);
		$search .= "(`callsign` like '%$searchtok%' or " .
			"`repeater` like '%$searchtok%' or " .
			"`repeaterid` like '%$searchtok%' or " .
			"`callsignid` like '%$searchtok%' or " .
			"`timeslot` like '%$searchtok%' or " .
			"`group` like '%$searchtok%' or " .
			"`name` like '%$searchtok%' or " .
			"`dtmf` like '%$searchtok%' or " .
			"`city` like '%$searchtok%' or " .
			"`country` like '%$searchtok%') ";
	}

	$sorting = sanitize($_GET['jtSorting']);
	$startindex = sanitize($_GET['jtStartIndex']);
	if (!ctype_digit($startindex))
		return;
	$pagesize = sanitize($_GET['jtPageSize']);
	if (!ctype_digit($pagesize))
		return;

	// Getting record count
	$result = $conn->query('select count(*) as `recordcount` from `' . DMR_LIVE_DB_TABLE . '` ' . $search);
	$row = $result->fetch_array();
	$recordcount = $row['recordcount'];

	$result = $conn->query('select *, unix_timestamp(`date`) as `ts` from `' . DMR_LIVE_DB_TABLE . '` ' . $search . 'order by ' . $conn->escape_string($sorting) .
		' limit ' . $conn->escape_string($startindex) . ',' . $conn->escape_string($pagesize));
	$rows = array();
	while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$row['date'] = date('H:i:s', $row['ts']);
	    $rows[] = $row;
	}

	$jtableresult = array();
	$jtableresult['Result'] = "OK";
	$jtableresult['TotalRecordCount'] = $recordcount;
	$jtableresult['Records'] = $rows;
	echo json_encode($jtableresult);

	$conn->close();
?>
