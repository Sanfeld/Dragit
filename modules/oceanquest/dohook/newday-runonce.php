<?php
	$sql = "SELECT acctid FROM ".db_prefix("accounts")."";
	$res = db_query($sql);
	for ($i=0;$i<db_num_rows($res);$i++){
		$row = db_fetch_assoc($res);
		$allprefs=unserialize(get_module_pref('allprefs','oceanquest',$row['acctid']));
		//Count down until the healer can fix the Decree
		if ($allprefs['healer']>0) $allprefs['healer']--;
		//Count down the Luckstar's arrival if the player has received a notary seal
		if ($allprefs['notary']==1){
			if ($allprefs['lscount']>0) $allprefs['lscount']--;
			else $allprefs['lscount']=get_module_setting("inport");
		}
		//Coconut
		$allprefs['coconut']=0;
		//fishing
		$allprefs['fishingtoday']=0;
		//drinking
		$allprefs['drinktoday']=0;
		//sailing on the luckstar
		$allprefs['luckstarsail']=0;
		//fishing map
		$allprefs['fishmap']=0;
		//captainspeak
		$allprefs['captaintalk']=0;
		//shipsearches
		$allprefs['shipsearches']=0;
		//gone to shore on island or pilinoria
		$allprefs['shore']=0;
		//Bear cave check - 40% chance a new bear goes into the cave
		if ($allprefs['bear']<1 && $allprefs['magicscroll']==1){
			if (e_rand(1,10)<5) $allprefs['bear']=1;
		}
		//king's furniture
		if ($allprefs['furniture']>3){
			if ($allprefs['furniture']==4) $allprefs['furniture']=1;
			elseif ($allprefs['furniture']==5) $allprefs['furniture']=2;
			elseif ($allprefs['furniture']==6) $allprefs['furniture']=3;
		}
		//Drinking from the stream
		$allprefs['stream']=0;
		//Trade item that was purchased by the store
		if ($allprefs['tradeitem']==6) $allprefs['tradeitem']=0;
		//did the player buy something from the tradestore today?
		$allprefs['purchased']=0;
		
		set_module_pref('allprefs',serialize($allprefs),'oceanquest',$row['acctid']);
	}
	for ($i=1;$i<=5;$i++){
		if (get_module_setting("tocome".$i)>0){
			increment_module_setting("avail".$i,get_module_setting("tocome".$i));
			set_module_setting("tocome".$i,0);
		}
	}
?>