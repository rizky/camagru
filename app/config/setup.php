<?php

if (isset($_GET['confirm'])) {
	require_once('database.php');
	$DB_DSN = 'mysql:host=db';
	$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	$sql = file_get_contents('table.sql');
	$qr = $db->exec($sql);
	echo "Done !<br><br>";
	echo "<a href='data.php'>Populate Data?</a>";
} else {
	echo "Are you sure ? <a href='?confirm=y'>Remove all data and recreate struct</a>";
}