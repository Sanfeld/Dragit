<?php
require_once(
"lib/showform.php");

if (httpget("act") == "save") {
	if (httpallpost()) {
		$post = httpallpost();
		if (is_array($post)) {
			reset($post);
			$sql = "INSERT INTO " . 
				db_prefix("races") . 
				" (basename," .
				"formalname," .
				"author," .
				"chooserace," .
				"setrace," .
				"location," .
				"deathchance," .
				"dragonkills)" .
				" VALUES " .
				"('{$post['basename']}'," .
				"'{$post['formalname']}'," .
				"'{$post['author']}'," .
				"'{$post['chooserace']}'," .
				"'{$post['setrace']}'," .
				"'{$post['location']}'," .
				"'{$post['deathchance']}'," .
				"'{$post['dragonkills']}')";
			db_query($sql);
			output("Addition of Race successful!");
		} else {
			output("Addition of Race failed, _POST is not an array!");
		}
	} else {
		output("Addition of Race failed, no _POST data.");
	}
}

require_once(
	"form.php");

if (httpallpost()) {
	$post = httpallpost();
} else {
	$post = array(
		"basename"=>"",
		"formalname"=>"",
		"author"=>"",
		"chooserace"=>"",
		"setrace"=>"",
		"location"=>"",
		"deathchance"=>"0",
		"dragonkills"=>"0"
	);
}

rawoutput("<form action='runmodule.php?module=race&op=add&act=save' method='POST'>");
	addnav("","runmodule.php?module=race&op=add&act=save");
showform($layout,$post);

?>