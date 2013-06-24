<?php
require_once(
"lib/showform.php");
require_once(
"select.php");

if (httpget("act") == "save") {
	require_once(
	"update.php");
	$race = httpget(
		"race");
	$new = httpallpost();
	$old = select_dorace(
		httpget(
			"race"));
	update_dorace(
		array(
			"race"=>$race,
			"new"=>$new,
			"old"=>$old
		)
	);	
}

require_once(
	"form.php");

if (httpallpost()) {
	$race = httpallpost();
} elseif (httpget("race")) {
	$race = select_dorace(
		httpget(
			"race"));
} else {
	$race = array(
		"basename"=>"",
		"formalname"=>"",
		"author"=>"",
		"chooserace"=>"",
		"setrace"=>"",
		"location"=>"Degolburg",
		"deathchance"=>"0",
		"dragonkills"=>"0"
	);
}

rawoutput("<form action='runmodule.php?module=race&op=edit&act=save&race=" .httpget("race"). "' method='POST'>");
	addnav("","runmodule.php?module=race&op=edit&act=save&race=" .httpget("race"). "");
showform($layout,$race);

?>