<?php

function sql_dorace(
	)
{
	$sql = array(
		"CREATE TABLE `" .db_prefix("races"). "` (".
			"`basename` varchar(28) NOT NULL default '',".
			"`formalname` varchar(48) NOT NULL default '',".
			"`author` varchar(48) NOT NULL default ''," .
			"`chooserace` text NOT NULL,".
			"`setrace` text NOT NULL,".
			"`location` varchar(28) NOT NULL default '',".
			"`deathchance` int(2) NOT NULL default '0',".
			"`dragonkills` int(11) NOT NULL default '0'".
		") ENGINE=InnoDB ;"
	);
	if (is_array($sql)) {
		return $sql;
	} else {
		return false;
	}
}

function table_dorace(
	$arg = ""
	)
{
	if ($arg == "install") {
		if (db_table_exists(db_prefix("races"))) {
			debug("Race table already exists.");
		} else {
			debug("Race table created.");
			$sql = sql_dorace();
			while (list($key,$val) = each($sql))
			{
				db_query($val);
			}
		}
	} elseif ($arg == "uninstall") {
		debug("Race table deleted.");
		db_query("DROP TABLE IF EXISTS " .
			db_prefix("races"));
	}
}

?>