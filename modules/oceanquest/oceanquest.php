<?php
	global $session;
	$op = httpget('op');
	$op2 = httpget('op2');
	$op3 = httpget('op3');
	$op4 = httpget('op4');
	$allprefs=unserialize(get_module_pref('allprefs'));
	require_once("modules/oceanquest/oceanquest_func.php");
	page_header("Ocean Quest");
	$knownmonsters = array('fishermanfight','fishcrew','fishshark','waterguardian','bear','pilinoriasoldier','reddragon','xavicon');
	if (in_array($op, $knownmonsters) || $op == "fight") {
		oceanquest_fight($op);
		die;
	}
	//villagenav();
	//addnav("Go to Pilinoria","runmodule.php?module=oceanquest&op=pilinoria&op2=landing");
if ($op=="superuser"){
	require_once("modules/allprefseditor.php");
	allprefseditor_search();
	page_header("Allprefs Editor");
	$subop=httpget('subop');
	$id=httpget('userid');
	addnav("Navigation");
	addnav("Return to the Grotto","superuser.php");
	villagenav();
	addnav("Edit user","user.php?op=edit&userid=$id");
	modulehook('allprefnavs');
	$allprefse=unserialize(get_module_pref('allprefs',"oceanquest",$id));
	if ($allprefse['lscount']=="") $allprefse['lscount']=0;
	if ($allprefse['drinktoday']=="") $allprefse['drinktoday']=0;
	if ($allprefse['round5']=="") $allprefse['round5']=0;
	if ($allprefse['round6']=="") $allprefse['round6']=0;
	if ($allprefse['round7']=="") $allprefse['round7']=0;
	if ($allprefse['round8']=="") $allprefse['round8']=0;
	if ($allprefse['bigfish']=="") $allprefse['bigfish']=0;
	if ($allprefse['fishweight']=="") $allprefse['fishweight']=0;
	if ($allprefse['numberfish']=="") $allprefse['numberfish']=0;
	if ($allprefse['dragonhp']=="") $allprefse['dragonhp']= "";
	if ($allprefse['xaviconhp']=="") $allprefse['xaviconhp']= "";
	if ($allprefse['dktrades']=="") $allprefse['dktrades']= 0;
	set_module_pref('allprefs',serialize($allprefse),'oceanquest',$id);
	if ($subop!='edit'){
		$allprefse=unserialize(get_module_pref('allprefs',"oceanquest",$id));
		$allprefse['piece1']= httppost('piece1');
		$allprefse['piece2']= httppost('piece2');
		$allprefse['piece3']= httppost('piece3');
		$allprefse['piece4']= httppost('piece4');
		$allprefse['healed']= httppost('healed');
		$allprefse['notary']= httppost('notary');
		$allprefse['lscount']= httppost('lscount');
		$allprefse['drinktoday']= httppost('drinktoday');
		$allprefse['round5']= httppost('round5');
		$allprefse['round6']= httppost('round6');
		$allprefse['round7']= httppost('round7');
		$allprefse['round8']= httppost('round8');
		$allprefse['unlockdecree']= httppost('unlockdecree');
		$allprefse['bait']= httppost('bait');
		$allprefse['pole']= httppost('pole');
		$allprefse['fishbook']= httppost('fishbook');
		$allprefse['readbook']= httppost('readbook');
		$allprefse['fishingtoday']= httppost('fishingtoday');
		$allprefse['iou1']= httppost('iou1');
		$allprefse['iou2']= httppost('iou2');
		$allprefse['iou3']= httppost('iou3');
		$allprefse['bigfish']= httppost('bigfish');
		$allprefse['fishweight']= httppost('fishweight');
		$allprefse['numberfish']= httppost('numberfish');
		$allprefse['forestworms']= httppost('forestworms');
		$allprefse['copperring']= httppost('copperring');
		$allprefse['fishmap']= httppost('fishmap');
		$allprefse['wind1']= httppost('wind1');
		$allprefse['depth1']= httppost('depth1');
		$allprefse['temp1']= httppost('temp1');
		$allprefse['wind2']= httppost('wind2');
		$allprefse['depth2']= httppost('depth2');
		$allprefse['temp2']= httppost('temp2');
		$allprefse['wind3']= httppost('wind3');
		$allprefse['depth3']= httppost('depth3');
		$allprefse['temp3']= httppost('temp3');
		$allprefse['wind4']= httppost('wind4');
		$allprefse['depth4']= httppost('depth4');
		$allprefse['temp4']= httppost('temp4');
		$allprefse['quality']= httppost('quality');
		$allprefse['pass']= httppost('pass');
		$allprefse['ironstar']= httppost('ironstar');
		$allprefse['direction']= httppost('direction');
		$allprefse['captainexp']= httppost('captainexp');
		$allprefse['sailfish']= httppost('sailfish');
		$allprefse['captaindinner']= httppost('captaindinner');
		$allprefse['captaintalk']= httppost('captaintalk');
		$allprefse['shipsearches']= httppost('shipsearches');
		$allprefse['pilinoria']= httppost('pilinoria');
		$allprefse['island']= httppost('island');
		$allprefse['firstisland']= httppost('firstisland');
		$allprefse['coconut']= httppost('coconut');
		$allprefse['opencave']= httppost('opencave');
		$allprefse['magicscroll']= httppost('magicscroll');
		$allprefse['bear']= httppost('bear');
		$allprefse['stream']= httppost('stream');
		$allprefse['landpil']= httppost('landpil');
		$allprefse['freed']= httppost('freed');
		$allprefse['outhouse']= httppost('outhouse');
		$allprefse['mystic']= httppost('mystic');
		$allprefse['furniture']= httppost('furniture');
		$allprefse['reddragon']= httppost('reddragon');
		$allprefse['dragonhp']= httppost('dragonhp');
		$allprefse['sorcerer']= httppost('sorcerer');
		$allprefse['xaviconhp']= httppost('xaviconhp');
		$allprefse['tradeitem']= httppost('tradeitem');
		$allprefse['purchased']= httppost('purchased');
		$allprefse['dktrades']= httppost('dktrades');
		$allprefse['shore']= httppost('shore');
		set_module_pref('allprefs',serialize($allprefse),'oceanquest',$id);
		output("Allprefs Updated`n");
		$subop="edit";
	}
	if ($subop=="edit"){
		require_once("lib/showform.php");
		$form = array(
			"Ocean Quest - The Docks,title",
			"piece1"=>"Does the player have the Forest Piece?,bool",
			"piece2"=>"Does the player have the HoF Piece?,bool",
			"piece3"=>"Does the player have the Baitshop Piece?,bool",
			"piece4"=>"Does the player have the Bar Piece?,bool",
			"healed"=>"Has the slip been healed?,enum,0,No,1,Yes,2,To Healer,3,Healed,4,Pickup",
			"notary"=>"Has the slip been notarized?,bool",
			"lscount"=>"How many days until luckstar is in port?,int",
			"drinktoday"=>"How many drinks has the player ordered today?,int",
			"round5"=>"How many rounds of Ale has the player purchased?,int",
			"round6"=>"How many rounds of Mead has the player purchased?,int",
			"round7"=>"How many rounds of Rum has the player purchased?,int",
			"round8"=>"How many rounds of Salty Dogs has the player purchased?,int",
			"unlockdecree"=>"Has the player chatted with Trandor?,bool",
			"Fishing,title",
			"bait"=>"Does the player have Bait?,bool",
			"pole"=>"Does the player have a Fishing Pole?,bool",
			"fishbook"=>"Does the player have the fish book?,bool",
			"readbook"=>"Has the player read the book at least once?,bool",
			"fishingtoday"=>"Number of times the player gone fishing today?,range,0,5,1",
			"iou1"=>"Has the player paid the IOU to Francis?,bool",
			"iou2"=>"Has the player paid the IOU to Yoglin?,bool",
			"iou3"=>"Has the player paid the IOU to Bondo?,bool",
			"bigfish"=>"Largest Fish Player has ever caught in ounces:,int",
			"fishweight"=>"How many ounces of fish has the player caught?,int",
			"numberfish"=>"How many fish has the player caught?,int",
			"forestworms"=>"Did the player find some Nightcrawlers to sell to Hoglin?,bool",
			"copperring"=>"Has the player found the copper ring?,bool",
			"fishmap"=>"Which Fishmap is being used on the Corinth today?,range,1,4,1",
			"wind1"=>"Wind at Spot 1:,range,1,30,1",
			"depth1"=>"Depth at Spot 1:,range,50,125,1",
			"temp1"=>"Temp at Spot 1:,range,60,90,1",
			"wind2"=>"Wind at Spot 2:,range,1,30,1",
			"depth2"=>"Depth at Spot 2:,range,50,125,1",
			"temp2"=>"Temp at Spot 2:,range,60,90,1",
			"wind3"=>"Wind at Spot 3:,range,1,30,1",
			"depth3"=>"Depth at Spot 3:,range,50,125,1",
			"temp3"=>"Temp at Spot 3:,range,60,90,1",
			"wind4"=>"Wind at Spot 4:,range,1,30,1",
			"depth4"=>"Depth at Spot 4:,range,50,125,1",
			"temp4"=>"Temp at Spot 4:,range,60,90,1",
			"quality"=>"Quality of fishing spot:,range,1,4,1",
			"pass"=>"Found a pass of the body of a Pilinorian Guard?,enum,0,No,1,Yes - Fishing,2,Yes - Fighting",
			"Sailing,title",
			"shore"=>"Has the player been to a shore today?,bool",
			"ironstar"=>"Has the player found the Iron Star?,bool",
			"direction"=>"What direction is your boat facing?,enum,0,East,1,West",
			"captainexp"=>"Has the player received permission to explore yet?,bool",
			"sailfish"=>"Has the player asked to go fishing on the Luckstar?,bool",
			"captaindinner"=>"Has the player had dinner with the captain?,bool",
			"captaintalk"=>"Has the player talked with the Captain today?,bool",
			"shipsearches"=>"How many searches has the player made today?,range,0,10,1",
			"pilinoria"=>"Did the player land on Pilinoria?,bool",
			"island"=>"Did the player land on South Island?,bool",
			"South Island,title",
			"firstisland"=>"Has the player done anything at the South Island?,enum,0,Nothing,1,Coconut,2,Stream,3,Coconut and Stream",
			"coconut"=>"Has the player found a coconut today?,enum,0,No,1,Yes - Not Sold,2,Yes - Sold",
			"opencave"=>"Has the player opened the cave?,bool",
			"magicscroll"=>"Does the player have the magic scroll?,bool",
			"bear"=>"Is there a bear in the cave?,bool",
			"stream"=>"Did they drink from the stream today?,bool",
			"Pilinoria,title",
			"landpil"=>"Has the player ever been to Pilinoria?,bool",
			"freed"=>"Has the king been freed from his spell?,enum,0,No,1,Yes,2,Yes - No Conference Yet",
			"outhouse"=>"Has the player used the outhouse in Pilinoria today?,bool",
			"mystic"=>"Has the player seen the Mystic yet,bool",
			"furniture"=>"What is the status of the furniture in the King's Room?,enum,0,All Intact,1,No Chair,2,No Table,3,No Chair or Table,4,No Chair Today,5,No Table Today,6,No Chair or Table Today",
			"reddragon"=>"Has the player killed the Red Dragon?,bool",
			"dragonhp"=>"How many hitpoints does the dragon have left,int",
			"sorcerer"=>"Has the sorceror been killed?,enum,0,No,1,Yes,2,Yes - To the King",
			"xaviconhp"=>"How many hitpoints does Xavicon have left?,int",
			"Trading,title",
			"tradeitem"=>"Which trade item has the player purchased?,enum,0,None,1,Item 1,2,Item 2,3,Item 3,4,Item 4,5,Item 5,6,Sold",
			"purchased"=>"Has the Player purchased something from the store today?,bool",
			"dktrades"=>"How many trades has the player made this dk?,int",
		);
		$allprefse=unserialize(get_module_pref('allprefs',"oceanquest",$id));
		rawoutput("<form action='runmodule.php?module=oceanquest&op=superuser&userid=$id' method='POST'>");
		showform($form,$allprefse,true);
		$click = translate_inline("Save");
		rawoutput("<input id='bsave' type='submit' class='button' value='$click'>");
		rawoutput("</form>");
		addnav("","runmodule.php?module=oceanquest&op=superuser&userid=$id");
	}
}

if ($op=="docks") {
	require_once("modules/oceanquest/oceanquest_docks.php");
	oceanquest_docks();
}
//HoF Decree: HoF Hook
if ($op=="documents"){
	output("`b`c`^Hall of Fame Documents`b`c`7`n");
	output("You wander over to the document archives and can't figure out why you never noticed this before.");
	output("`n`nYou look through tons of records and papers that indicate how successful each individual in the kingdom is. It starts to get boring and you realize why you never came here before.");
	output("`n`nSuddenly, a slip of paper catches your eye.  This one seems interesting.  You flip it over and instantly recognize it...");
	output("`n`nYou've found one of the pieces of the `^Royal Decree of Passage`7!!");
	output("`n`nYou tuck the piece of paper away and start coughing.  Seems like you've inhaled a lot of dust.  You make a mental note to never come back here again.");
	apply_buff('buzzkill',array(
		"name"=>"`)Dust Cough",
		"rounds"=>10,
		"wearoff"=>"Your lungs clear.",
		"atkmod"=>.92,
		"roundmsg"=>"You're attack is weaker because you're still coughing.",
	));
	$allprefs['piece2']=1;
	set_module_pref('allprefs',serialize($allprefs));
	addnav("Back to HoF","hof.php");
	villagenav();
}
//Healing the Decree: Healer Hook
if ($op=="healpaper"){
	page_header("Healer's Hut");
	output("`b`c`#Healer's Hut`b`c`n`3");
	if ($allprefs['healed']==2){
		if ($session['user']['gold']>=1000){
			output("You hand over your `^1000 gold`3 to the healer and then give him your precious `^4 pieces of the Decree of Passage`3 and hope for the best.");
			output("`n`n\"`6Come back tomorrow and it will be ready for you.`3\"");
			$allprefs['healed']=3;
			$session['user']['gold']-=1000;
			set_module_pref('allprefs',serialize($allprefs));
		}else{
			output("You're a little short on gold right now. Perhaps you should come back later.");
		}
	}elseif($allprefs['healed']==3){
		output("You ask if your paper is healed yet and the healer just ignores you.  You ask again and he looks up and says \"`6What part of 'COME BACK TOMORROW' didn't you understand??`3\"");
		output("`n`nMaybe it'd be better if you just come back tomorrow.");
	}elseif($allprefs['healed']==4){
		$allprefs['healed']=1;
		set_module_pref('allprefs',serialize($allprefs));
		output("You come to pick up your `^Decree of Passage`3 and it's healed! Now it's time to board the `i`^Luckstar`i`3!!`n`n");
		rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/recovered.gif></td></tr></table></center><br>");
	}
	if (is_module_active("potions") && get_module_setting("movehealer","potions") == "1") $ret="village.php";
	else $ret="forest.php";
	addnav("Continue","healer.php?return=".$ret);
}
//Notarize: Bank Hook
if ($op=="notarize"){
	page_header("Ye Olde Bank");
	output("`c`b`^Ye Olde Bank`c`b`6");
	output("You pull out your `^Royal Decree of Passage`6 and `@Elessa`6 looks it over. `n`n\"`@Yes, this is an official decree and I can notarize this document.");
	if ($session['user']['goldinbank']>10000){
		output("Luckily for you, notarization for clients with more than `^10,000 gold`@ in the bank can receive free notarization.`6\"");
		addnav("Notarize","runmodule.php?module=oceanquest&op=notarypay&op2=1");
	}else{
		output("Unfortunately, only clients with more than `^10,000 gold`@ in the bank can receive free notarization.  Otherwise, it will cost you `^500 gold`@ for me to do that for you.`6\"");
		addnav("Notarize","runmodule.php?module=oceanquest&op=notarypay&op2=2");
	}
	addnav("Back to the Bank","bank.php");
}
if ($op=="notarypay"){
	page_header("Ye Olde Bank");
	output("`c`b`^Ye Olde Bank`c`b`6");
	if ($op2==2){
		if ($session['user']['gold']>=500){
			output("You hand over `^500 gold`6 and");
			$op2=1;
			$session['user']['gold']-=500;
		}elseif ($session['user']['goldinbank']>=500){
			output("`@Elessa`6 takes the `^500 gold`6 from your bank account.");
			$op2=1;
			$session['user']['goldinbank']-=500;
		}else{
			output("`@Elessa `6explains the part about requiring you to maintain `^10,000 gold`6 in your account or pay `^500 gold`6 to have documents notarized. She points out how you have neither.");
		}
	}
	if ($op2==1){
		output("`6She pulls out a large Notary Seal and takes your `^Decree of Passage`6 and clamps down on the bottom left corner.");
		output("She hands it back to you with a smile. \"`@Anything else I can do for you?`6\"`n`n");
		rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/notarized.gif></td></tr></table></center><br>");
		$allprefs['notary']=1;
		set_module_pref('allprefs',serialize($allprefs));
	}
	addnav("Back to the Bank","bank.php");
}
if ($op=="explore") {
	require_once("modules/oceanquest/oceanquest_explore.php");
	oceanquest_explore();
}
if ($op=="fishingexpedition"){
	require_once("modules/oceanquest/oceanquest_fishingexpedition.php");
	oceanquest_fishingexpedition();
}
if ($op=="fishingexpeditiona"){
	require_once("modules/oceanquest/oceanquest_fishingexpeditiona.php");
	oceanquest_fishingexpeditiona();
}
if ($op=="sailing"){
	require_once("modules/oceanquest/oceanquest_sailing.php");
	oceanquest_sailing();
}
if ($op=="sailinga"){
	require_once("modules/oceanquest/oceanquest_sailinga.php");
	oceanquest_sailinga();
}
if ($op=="island"){
	require_once("modules/oceanquest/oceanquest_island.php");
	oceanquest_island();
}
if ($op=="pilinoria"){
	require_once("modules/oceanquest/oceanquest_pilinoria.php");
	oceanquest_pilinoria();
}
if ($op=="throne"){
	require_once("modules/oceanquest/oceanquest_throne.php");
	oceanquest_throne();
}
if ($op=="thronea"){
	require_once("modules/oceanquest/oceanquest_thronea.php");
	oceanquest_thronea();
}
if ($op=="hof"){
	require_once("modules/oceanquest/oceanquest_hof.php");
	oceanquest_hof();
}
page_footer();
?>