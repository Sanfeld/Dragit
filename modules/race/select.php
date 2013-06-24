<?php
function select_dorace(
	$arg = ''
	)
{
	$race = array();
	$result = db_query("SELECT * FROM " .
		db_prefix('races') .
			" WHERE basename='$arg'");
	$race = db_fetch_assoc($result);
	return $race;
}
?>