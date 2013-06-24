<?php
function lostruins_goldgift(){
	global $session;
	$exploreturns=get_module_setting("exploreturns");
	$allprefs=unserialize(get_module_pref('allprefs'));
	$usedexpts=$allprefs['usedexpts'];
	$who = httpget('who');
	if ($who==""){
		output("`n`7'Who will receive the `^gold`7?'`n");
		$subop = httpget('subop');
		if ($subop!="search"){
			$search = translate_inline("Search");
			rawoutput("<form action='runmodule.php?module=lostruins&op=goldgift&subop=search' method='POST'><input name='name' id='name'><input type='submit' class='button' value='$search'></form>");
			addnav("","runmodule.php?module=lostruins&op=goldgift&subop=search");
			addnav("Search","runmodule.php?module=lostruins&op=goldgift");
			rawoutput("<script language='JavaScript'>document.getElementById('name').focus();</script>");
		}else{
			addnav("Search Again","runmodule.php?module=lostruins&op=goldgift");
			$search = "%";
			$name = httppost('name');
			for ($i=0;$i<strlen($name);$i++){
				$search.=substr($name,$i,1)."%";
			}
			$sql = "SELECT name,alive,location,sex,level,laston,loggedin,login FROM " . db_prefix("accounts") . " WHERE (locked=0 AND name LIKE '$search') ORDER BY level DESC";
			$result = db_query($sql);
			$max = db_num_rows($result);
			if ($max > 100) {
				output("`n`n`7No.  That's too many names to pick from.");
				output("I'll let you choose from the first couple...`n");
				$max = 100;
			}
			$n = translate_inline("Name");
			$lev = translate_inline("Level");
			rawoutput("<table border=0 cellpadding=0><tr><td>$n</td></tr>");
			for ($i=0;$i<$max;$i++){
				$row = db_fetch_assoc($result);
				rawoutput("<tr><td><a href='runmodule.php?module=lostruins&op=goldgift&who=".rawurlencode($row['login'])."'>");
				output_notl("%s", $row['name']);
				rawoutput("</a></td></tr>");
				addnav("","runmodule.php?module=lostruins&op=goldgift&who=".rawurlencode($row['login']));
			}
		rawoutput("</table>");
		}
	}else{
		output("`n`c`b`5G`6ift `5O`6f `5G`6old`c`b`n");
		$sql = "SELECT name,acctid FROM " . db_prefix("accounts") . " WHERE login='$who'";
		$result = db_query($sql);
		if (db_num_rows($result)>0){
			$row = db_fetch_assoc($result);
			$id = $row['acctid'];
			$name = $row['name'];
			if ($usedexpts<$exploreturns) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
			addnav("V?(V) Return to Village","village.php");
			if ($name==$session['user']['name']){
				output("`Q'NO!");
				output("I will not let you be so greedy and choose yourself to receive this gift.");
				output("In fact, your greed disappoints me.");
				output("Perhaps I shall take all your gold so you can contemplate your greed in poverty.'`n`n");
				$session['user']['gold']=0;
			}else{
				$case18g=get_module_setting("case18g");
				if ($case18g=="") $case18g=0;
				output("`Q'Yes, that's a wonderful choice; `^%s gold`Q will be delivered to the bank account of`# %s`Q promptly.",$case18g,$name);
				output("I'll also send them a letter informing them of your good deed.");
				output("Good day!'");
				output("`n`n`7With those words, `&Lakinne the Great`7 disappears.");
				require_once("lib/systemmail.php");
				$subj = sprintf("You've received a gift from the `5A`6ncient `5R`6uins");
				$body = sprintf("`^Dear %s`^,`n`nIt is my pleasure to inform you that thanks to the generosity of %s`^ you have received %s gold deposited into your bank account.`n`nSincerely,`n`n  `&Lakinne the Great`7 of the `5A`6ncient `5R`6uins.",$name,$session['user']['name'],$case18g);
				systemmail($id,$subj,$body);
				$sql = "UPDATE ". db_prefix("accounts") . " SET goldinbank=goldinbank+$case18g WHERE acctid=$id";
				db_query($sql);
			}
		}else{
			output("'`7Heh...  I don't know anyone named that.'");
        }
	}
}
?>