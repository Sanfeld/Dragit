<?php
if (httpget("race")) {
	$bname = httpget("race");
	db_query("DELETE FROM " .
		db_prefix("races") . " " .
		"WHERE basename='$bname'");
	output("Delete of Race successful!");
}
?>