<?php

function update_dorace(
	)
{
	if (func_get_arg(0)) {
		$arg = func_get_arg(0);
		if (is_array($arg)) {
			reset($arg);
			if ($arg['race'] && $arg['new'] && $arg['old']) {
				output("Edit of Race successful!");
				$race = $arg['race'];
				$new = $arg['new'];
				$old = $arg['old'];
				if ($new['basename'] != $old['basename']) {
					db_query("UPDATE " .
						db_prefix("races") . 
						" SET basename='{$new['basename']}'" .
						" WHERE basename='$race'");
				}
				if ($new['formalname'] != $old['formalname']) {
					db_query("UPDATE " .
						db_prefix("races") .
						" SET formalname='{$new['formalname']}'" .
						" WHERE basename='$race'");
				}
				if ($new['author'] != $old['author']) {
					db_query("UPDATE " .
						db_prefix("races") .
						" SET author='{$new['author']}'" .
						" WHERE basename='$race'");
				}
				if ($new['chooserace'] != $old['chooserace']) {
					db_query("UPDATE " .
						db_prefix("races") .
						" SET chooserace='{$new['chooserace']}'" .
						" WHERE basename='$race'");
				}
				if ($new['setrace'] != $old['setrace']) {
					db_query("UPDATE " .
						db_prefix("races") .
						" SET setrace='{$new['setrace']}'" .
						" WHERE basename='$race'");
				}
				if ($new['location'] != $old['location']) {
					db_query("UPDATE " .
						db_prefix("races") .
						" SET location='{$new['location']}'" .
						" WHERE basename='$race'");
				}
				if ($new['deathchance'] != $old['deathchance']) {
					db_query("UPDATE " .
						db_prefix("races") .
						" SET deathchance='{$new['deathchance']}'" .
						" WHERE basicname='$race'");
				}
				if ($new['dragonkills'] != $old['dragonkills']) {
					db_query("UPDATE " .
						db_prefix("races") .
						" SET dragonkills='{$new['dragonkills']}'" .
						" WHERE basename='$race'");
				}
			} else {
				output("Edit of Race failed, missing key for _POST array!");
			}
		} else {
			output("Edit of Race failed, _POST is not an array!");
		}
	} else {
		return false;
	}
}

?>