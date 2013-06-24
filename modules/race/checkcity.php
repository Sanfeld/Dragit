<?php

function checkcity_dorace(
	$arg = ""
	)
{
	global $session;
	if (is_array($arg)) {
		$bname = $race['basename'];
		$location = $race['location'];
		if ($session['user']['race'] == $race) {
			if (is_module_active("cities")) {
				if (get_module_pref("homecity","cities") != $location) {
					set_module_pref("homecity",$location,"cities");
					return true;
				}
			}
		}
	}
}

?>