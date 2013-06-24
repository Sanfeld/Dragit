<?php
global $session;

	global $session;
	page_header("Magical Arena");
	$aname = get_module_setting("name2","arena");
	$id = $session['user']['acctid'];
	$op=httpget('op');
	$p1 = get_module_pref("Vires");
	$p2 = get_module_pref("ValdeVires");
	$p3 = get_module_pref("Tutaminis");
	$p4 = get_module_pref("Navitas");
	$p5 = get_module_pref("ConfutoNavitas");
	$p6 = get_module_pref("Navi");
	$p7 = get_module_pref("Diligo");
	$p8 = get_module_pref("Abominor");
	$p9 = get_module_pref("Fragilitas");
	$p10 = get_module_pref("ParumNex");
	$p11 = get_module_pref("PropinquusutNex");
	$p12 = get_module_pref("LevoutSublimitas");
	$p13 = get_module_pref("Umbra");
	$p14 = get_module_pref("FlammaofAbyssus");
	$pt=$p1+$p2+$p3+$p4+$p5+$p6+$p7+$p8+$p9+$p10+$p11+$p12+$p13+$p14;
	$n1 = "Vires";
	$n2 = "ValdeVires";
	$n3 = "Tutaminis";
	$n4 = "Navitas";
	$n5 = "ConfutoNavitas";
	$n6 = "Navi";
	$n7 = "Diligo";
	$n8 = "Abominor";
	$n9 = "Fragilitas";
	$n10 = "ParumNex";
	$n11 = "PropinquusutNex";
	$n12 = "LevoutSublimitas";
	$n13 = "Umbra";
	$n14 = "FlammaofAbyssus";
	$i0=get_module_pref("monkhood","witchgarden",$id);
	$i1=get_module_pref("venom","witchgarden",$id);
	$i2=get_module_pref("hemlock","witchgarden",$id);
	$i3=get_module_pref("mandrake","witchgarden",$id);
	$i4=get_module_pref("scale","witchgarden",$id);
	$it = $i0+$i1+$i2+$i3+$i4;
	//mix items
	if ($op=="potions"){

	}
	if ($op=="mixp"){
		output("`b`c`2P`6o`3t`6i`2o`6n`3s`b`c");
		if ($pt>=20){
			output("`%Sorry, you are unable to create any more potions until you sell or use some");
			addnav("Sell some","runmodule.php?module=magicarena&op=shopsell");
		}elseif ($it<5){
			output("`%Sorry, you are unable to mix any ingredients, as you have less than the minimum of 5 onhand");
		}else{
			output("`%Please select the ingredients you wish to mix, in the order you wish to mix them");
			output_notl("`n`n");
			output("Every 20 potions created, will increase your potion level for that potion, making it stronger in the magical arena");
			output_notl("`n`n");
			output("Also note, though these potions are powerful, some at a low level, will have none to little effect, you must spend the time to make them level up, before their true power will be revealed");
			output_notl("`n`n");
			rawoutput("<form action='runmodule.php?module=magicarena&op=mixfinish' method='POST'>");
			$form = array(
            	"Ingredient Mix,note",
				"ingredient1"=>"Ingredient1,enum,0,monkhood,1,basilisk venom,2,hemlock,3,mandrake,4,obsidian dragon scale",
             	"ingredient2"=>"Ingredient2,enum,0,monkhood,1,basilisk venom,2,hemlock,3,mandrake,4,obsidian dragon scale",
             	"ingredient3"=>"Ingredient3,enum,0,monkhood,1,basilisk venom,2,hemlock,3,mandrake,4,obsidian dragon scale",
             	"ingredient4"=>"Ingredient4,enum,0,monkhood,1,basilisk venom,2,hemlock,3,mandrake,4,obsidian dragon scale",
             	"ingredient5"=>"Ingredient5,enum,0,monkhood,1,basilisk venom,2,hemlock,3,mandrake,4,obsidian dragon scale",
         		);
      		require_once("lib/showform.php");
			showform($form, array(), true);
			$mix = translate_inline("Mix Potion");
			rawoutput("<input type='submit' class='button' value='$mix'>");
			rawoutput("</form>");
			addnav("", "runmodule.php?module=magicarena&op=mixfinish");

		}
		addnav("Clan Halls","clan.php");
	}
	if ($op=="mixfinish"){
		$ingredient1=httppost('ingredient1');
		$ingredient2=httppost('ingredient2');
		$ingredient3=httppost('ingredient3');
		$ingredient4=httppost('ingredient4');
		$ingredient5=httppost('ingredient5');
		$mix=-1;

		if ($ingredient1==0 && $ingredient2==1 && $ingredient3==0 && $ingredient4==1 && $ingredient5==3){
			$mix=1;
			if ($i0<2 || $i1<2 ||$i3<1){
				output("You have not the necessary ingredients to make this potion");
			}else{
				output("You have created %s",$n1);
				$new=$p1+1;
				if (get_module_pref("level1")==0){
					set_module_pref("level1",1);
				}
				set_module_pref($n1,$new,"magicarena",$id);
				$try=get_module_pref("made1")+1;
				if ($try==20){
					$levela=get_module_pref("level1");
					$level=get_module_pref("level1")+1;
					if ($levela==20){
						output_notl("`n`n");
						output("You have mastered this potion and are at level 50, you cannot advance any further in levels");
					}elseif ($levela<20){
						output_notl("`n`n");
						output("You gain a level to %s in Potion making for this potion",$try);
						set_module_pref("level1",$level);
						clear_module_pref("made1");
					}
				}elseif($try<20){
					output_notl("`n`n");
					output("You have created %s of this potion",$try);
					set_module_pref("made1",$try);
				}
				$new0=$i0-2;
				if ($new0==0){
					clear_module_pref("monkhood","witchgarden",$id);
				}elseif ($new0<>0){
					set_module_pref("monkhood",$new0,"witchgarden",$id);
				}
				$new1=$i1-2;
				if ($new1==0){
					clear_module_pref("venom","witchgarden",$id);
				}elseif ($new1<>0){
					set_module_pref("venom",$new1,"witchgarden",$id);
				}
				$new3=$i3-1;
				if ($new3==0){
					clear_module_pref("mandrake","witchgarden",$id);
				}elseif ($new3<>0){
					set_module_pref("mandrake",$new3,"witchgarden",$id);
				}

			}
		}
		if ($ingredient1==0 && $ingredient2==0 && $ingredient3==0 && $ingredient4==4 && $ingredient5==3){
			$mix=1;
			if ($i0<3 || $i3<1 ||$i4<1){
				output("You have not the necessary ingredients to make this potion");
			}else{
				output("You have created %s",$n2);
				$new=$p2+1;
				if (get_module_pref("level2")==0){
					set_module_pref("level2",1);
				}
				set_module_pref($n2,$new,"magicarena",$id);
				$try=get_module_pref("made2")+1;
				if ($try==20){
					$levela=get_module_pref("level2");
					$level=get_module_pref("level2")+1;
					if ($levela==20){
						output_notl("`n`n");
						output("You have mastered this potion and are at level 50, you cannot advance any further in levels");
					}elseif ($levela<20){
						output_notl("`n`n");
						output("You gain a level to %s in Potion making for this potion",$try);
						set_module_pref("level2",$level);
						clear_module_pref("made2");
					}
				}elseif($try<20){
					output_notl("`n`n");
					output("You have created %s of this potion",$try);
					set_module_pref("made2",$try);
				}
				$new0=$i0-3;
				if ($new0==0){
					clear_module_pref("monkhood","witchgarden",$id);
				}elseif ($new0<>0){
					set_module_pref("monkhood",$new0,"witchgarden",$id);
				}
				$new3=$i3-1;
				if ($new3==0){
					clear_module_pref("mandrake","witchgarden",$id);
				}elseif ($new3<>0){
					set_module_pref("mandrake",$new3,"witchgarden",$id);
				}
				$new4=$i4-1;
				if ($new4==0){
					clear_module_pref("scale","witchgarden",$id);
				}elseif ($new4<>0){
					set_module_pref("scale",$new4,"witchgarden",$id);
				}
			}
		}
		if ($ingredient1==1 && $ingredient2==3 && $ingredient3==0 && $ingredient4==3 && $ingredient5==1){
			$mix=1;
			if ($i0<1 || $i1<2 || $i3<2){
				output("You have not the necessary ingredients to make this potion");
			}else{
				output("You have created %s",$n3);
				if (get_module_pref("level3")==0){
					set_module_pref("level3",1);
				}
				$try=get_module_pref("made3")+1;
				if ($try==20){
					$levela=get_module_pref("level3");
					$level=get_module_pref("level3")+1;
					if ($levela==20){
						output_notl("`n`n");
						output("You have mastered this potion and are at level 50, you cannot advance any further in levels");
					}elseif ($levela<20){
						output_notl("`n`n");
						output("You gain a level to %s in Potion making for this potion",$try);
						set_module_pref("level3",$level);
						clear_module_pref("made3");
					}
				}elseif($try<20){
					output_notl("`n`n");
					output("You have created %s of this potion",$try);
					set_module_pref("made3",$try);
				}
				$new=$p3+1;
				set_module_pref($n3,$new,"magicarena",$id);
				$new0=$i0-1;
				if ($new0==0){
					clear_module_pref("monkhood","witchgarden",$id);
				}elseif ($new0<>0){
					set_module_pref("monkhood",$new0,"witchgarden",$id);
				}
				$new1=$i1-2;
				if ($new1==0){
					clear_module_pref("venom","witchgarden",$id);
				}elseif ($new1<>0){
					set_module_pref("venom",$new1,"witchgarden",$id);
				}
				$new3=$i3-2;
				if ($new3==0){
					clear_module_pref("mandrake","witchgarden",$id);
				}elseif ($new3<>0){
					set_module_pref("mandrake",$new3,"witchgarden",$id);
				}
			}
		}
		if ($ingredient1==3 && $ingredient2==1 && $ingredient3==1 && $ingredient4==3 && $ingredient5==3){
			$mix=1;
		if ($i1<2 || $i3<3){
				output("You have not the necessary ingredients to make this potion");
			}else{
				output("You have created %s",$n4);
				if (get_module_pref("level4")==0){
					set_module_pref("level4",1);
				}
				$try=get_module_pref("made4")+1;
				if ($try==20){
					$levela=get_module_pref("level4");
					$level=get_module_pref("level4")+1;
					if ($levela==20){
						output_notl("`n`n");
						output("You have mastered this potion and are at level 50, you cannot advance any further in levels");
					}elseif ($levela<20){
						output_notl("`n`n");
						output("You gain a level to %s in Potion making for this potion",$try);
						set_module_pref("level4",$level);
						clear_module_pref("made4");
					}
				}elseif($try<20){
					output_notl("`n`n");
					output("You have created %s of this potion",$try);
					set_module_pref("made4",$try);
				}
				$new=$p4+1;
				set_module_pref($n4,$new,"magicarena",$id);
				$new1=$i1-2;
				if ($new1==0){
					clear_module_pref("venom","witchgarden",$id);
				}elseif ($new1<>0){
					set_module_pref("venom",$new1,"witchgarden",$id);
				}
				$new3=$i3-3;
				if ($new3==0){
					clear_module_pref("mandrake","witchgarden",$id);
				}elseif ($new3<>0){
					set_module_pref("mandrake",$new3,"witchgarden",$id);
				}
			}
		}
		if ($ingredient1==1 && $ingredient2==3 && $ingredient3==1 && $ingredient4==4 && $ingredient5==1){
			$mix=1;
			if ($i1<3 || $i3<1 || $i4<1){
				output("You have not the necessary ingredients to make this potion");
			}else{
				output("You have created %s",$n5);
				if (get_module_pref("level5")==0){
					set_module_pref("level5",1);
				}
				$new=$p5+1;
				set_module_pref($n5,$new,"magicarena",$id);
				$try=get_module_pref("made5")+1;
				if ($try==20){
					$level=get_module_pref("level5")+1;
					$levela=get_module_pref("level5");
					if ($levela==20){
						output_notl("`n`n");
						output("You have mastered this potion and are at level 50, you cannot advance any further in levels");
					}elseif ($levela<20){
						output_notl("`n`n");
						output("You gain a level to %s in Potion making for this potion",$try);
						set_module_pref("level5",$level);
						clear_module_pref("made5");
					}
				}elseif($try<20){
					output_notl("`n`n");
					output("You have created %s of this potion",$try);
					set_module_pref("made5",$try);
				}
				$new1=$i1-3;
				if ($new1==0){
					clear_module_pref("venom","witchgarden",$id);
				}elseif ($new1<>0){
					set_module_pref("venom",$new1,"witchgarden",$id);
				}
				$new3=$i3-1;
				if ($new3==0){
					clear_module_pref("mandrake","witchgarden",$id);
				}elseif ($new3<>0){
					set_module_pref("mandrake",$new3,"witchgarden",$id);
				}
				$new4=$i4-1;
				if ($new4==0){
					clear_module_pref("scale","witchgarden",$id);
				}elseif ($new4<>0){
					set_module_pref("scale",$new4,"witchgarden",$id);
				}
			}
		}
		if ($ingredient1==4 && $ingredient2==1 && $ingredient3==3 && $ingredient4==3 && $ingredient5==1){
			$mix=1;
			if ($i1<2 || $i3<2 || $i4<1){
				output("You have not the necessary ingredients to make this potion");
			}else{
				output("You have created %s",$n6);
				if (get_module_pref("level6")==0){
					set_module_pref("level6",1);
				}
				$new=$p6+1;
				set_module_pref($n6,$new,"magicarena",$id);
				$try=get_module_pref("made6")+1;
				if ($try==20){
					$levela=get_module_pref("level6");
					$level=get_module_pref("level6")+1;
					if ($levela==20){
						output_notl("`n`n");
						output("You have mastered this potion and are at level 50, you cannot advance any further in levels");
					}elseif ($levela<20){
						output_notl("`n`n");
						output("You gain a level to %s in Potion making for this potion",$try);
						set_module_pref("level6",$level);
						clear_module_pref("made6");
					}
				}elseif($try<20){
					output_notl("`n`n");
					output("You have created %s of this potion",$try);
					set_module_pref("made6",$try);
				}
				$new1=$i1-2;
				if ($new1==0){
					clear_module_pref("venom","witchgarden",$id);
				}elseif ($new1<>0){
					set_module_pref("venom",$new1,"witchgarden",$id);
				}
				$new3=$i3-2;
				if ($new3==0){
					clear_module_pref("mandrake","witchgarden",$id);
				}elseif ($new3<>0){
					set_module_pref("mandrake",$new3,"witchgarden",$id);
				}
				$new4=$i4-1;
				if ($new4==0){
					clear_module_pref("scale","witchgarden",$id);
				}elseif ($new4<>0){
					set_module_pref("scale",$new4,"witchgarden",$id);
				}
			}
		}
		if ($ingredient1==2 && $ingredient2==0 && $ingredient3==2 && $ingredient4==1 && $ingredient5==2){
			$mix=1;
			if ($i0<1 || $i1<1 || $i2<3){
				output("You have not the necessary ingredients to make this potion");
			}else{
				output("You have created %s",$n7);
				if (get_module_pref("level7")==0){
					set_module_pref("level7",1);
				}
				$new=$p7+1;
				set_module_pref($n7,$new,"magicarena",$id);
				$try=get_module_pref("made7")+1;
				if ($try==20){
					$levela=get_module_pref("level7");
					$level=get_module_pref("level7")+1;
					if ($levela==20){
						output_notl("`n`n");
						output("You have mastered this potion and are at level 50, you cannot advance any further in levels");
					}elseif ($levela<20){
						output_notl("`n`n");
						output("You gain a level to %s in Potion making for this potion",$try);
						set_module_pref("level7",$level);
						clear_module_pref("made7");
					}
				}elseif($try<20){
					output_notl("`n`n");
					output("You have created %s of this potion",$try);
					set_module_pref("made7",$try);
				}
				$new0=$i0-1;
				if ($new0==0){
					clear_module_pref("monkhood","witchgarden",$id);
				}elseif ($new0<>0){
					set_module_pref("monkhood",$new0,"witchgarden",$id);
				}
				$new1=$i1-1;
				if ($new1==0){
					clear_module_pref("venom","witchgarden",$id);
				}elseif ($new1<>0){
					set_module_pref("venom",$new1,"witchgarden",$id);
				}
				$new2=$i2-2;
				if ($new2==0){
					clear_module_pref("hemlock","witchgarden",$id);
				}elseif ($new2<>0){
					set_module_pref("hemlock",$new2,"witchgarden",$id);
				}
			}
		}
		if ($ingredient1==3 && $ingredient2==0 && $ingredient3==3 && $ingredient4==1 && $ingredient5==3){
			$mix=1;
			if ($i0<1 || $i1<1 || $i3<3){
				output("You have not the necessary ingredients to make this potion");
			}else{
				output("You have created %s",$n8);
				if (get_module_pref("level8")==0){
					set_module_pref("level8",1);
				}
				$new=$p8+1;
				set_module_pref($n8,$new,"magicarena",$id);
				$try=get_module_pref("made8")+1;
				if ($try==20){
					$levela=get_module_pref("level8");
					$level=get_module_pref("level8")+1;
					if ($levela==20){
						output_notl("`n`n");
						output("You have mastered this potion and are at level 50, you cannot advance any further in levels");
					}elseif ($levela<20){
						output_notl("`n`n");
						output("You gain a level to %s in Potion making for this potion",$try);
						set_module_pref("level8",$level);
						clear_module_pref("made8");
					}
				}elseif($try<20){
					output_notl("`n`n");
					output("You have created %s of this potion",$try);
					set_module_pref("made8",$try);
				}
				$new0=$i0-1;
				if ($new0==0){
					clear_module_pref("monkhood","witchgarden",$id);
				}elseif ($new0<>0){
					set_module_pref("monkhood",$new0,"witchgarden",$id);
				}
				$new1=$i1-1;
				if ($new1==0){
					clear_module_pref("venom","witchgarden",$id);
				}elseif ($new1<>0){
					set_module_pref("venom",$new1,"witchgarden",$id);
				}
				$new3=$i3-3;
				if ($new3==0){
					clear_module_pref("mandrake","witchgarden",$id);
				}elseif ($new3<>0){
					set_module_pref("mandrake",$new3,"witchgarden",$id);
				}
			}
		}
		if ($ingredient1==2 && $ingredient2==2 && $ingredient3==3 && $ingredient4==2 && $ingredient5==2){
			$mix=1;
			if ($i2<4 || $i3<1){
				output("You have not the necessary ingredients to make this potion");
			}else{
				output("You have created %s",$n9);
				$new=$p9+1;
				set_module_pref($n9,$new,"magicarena",$id);
				$try=get_module_pref("made9")+1;
				if (get_module_pref("level9")==0){
					set_module_pref("level9",1);
				}
				if ($try==20){
					$levela=get_module_pref("level9");
					$level=get_module_pref("level9")+1;
					if ($levela==20){
						output_notl("`n`n");
						output("You have mastered this potion and are at level 50, you cannot advance any further in levels");
					}elseif ($levela<20){
						output_notl("`n`n");
						output("You gain a level to %s in Potion making for this potion",$try);
						set_module_pref("level9",$level);
						clear_module_pref("made9");
					}
				}elseif($try<20){
					output_notl("`n`n");
					output("You have created %s of this potion",$try);
					set_module_pref("made9",$try);
				}
				$new2=$i2-4;
				if ($new2==0){
					clear_module_pref("hemlock","witchgarden",$id);
				}elseif ($new2<>0){
					set_module_pref("hemlock",$new2,"witchgarden",$id);
				}
				$new3=$i3-1;
				if ($new3==0){
					clear_module_pref("mandrake","witchgarden",$id);
				}elseif ($new3<>0){
					set_module_pref("mandrake",$new3,"witchgarden",$id);
				}
			}
		}
		if ($ingredient1==0 && $ingredient2==3 && $ingredient3==2 && $ingredient4==1 && $ingredient5==0){
			$mix=1;
			if ($i0<2 || $i1<1 || $i2< 1 || $i3<1){
				output("You have not the necessary ingredients to make this potion");
			}else{
				output("You have created %s",$n10);
				$new=$p10+1;
				set_module_pref($n10,$new,"magicarena",$id);
				if (get_module_pref("level10")==0){
					set_module_pref("level10",1);
				}
				$try=get_module_pref("made10")+1;
				if ($try==20){
					$levela=get_module_pref("level10");
					$level=get_module_pref("level10")+1;
					if ($levela==20){
						output_notl("`n`n");
						output("You have mastered this potion and are at level 50, you cannot advance any further in levels");
					}elseif ($levela<20){
						output_notl("`n`n");
						output("You gain a level to %s in Potion making for this potion",$try);
						set_module_pref("level10",$level);
						clear_module_pref("made10");
					}
				}elseif($try<20){
					output_notl("`n`n");
					output("You have created %s of this potion",$try);
					set_module_pref("made10",$try);
				}
				$new0=$i0-2;
				if ($new0==0){
					clear_module_pref("monkhood","witchgarden",$id);
				}elseif ($new0<>0){
					set_module_pref("monkhood",$new0,"witchgarden",$id);
				}
				$new1=$i1-1;
				if ($new1==0){
					clear_module_pref("venom","witchgarden",$id);
				}elseif ($new1<>0){
					set_module_pref("venom",$new1,"witchgarden",$id);
				}
				$new2=$i2-1;
				if ($new2==0){
					clear_module_pref("hemlock","witchgarden",$id);
				}elseif ($new2<>0){
					set_module_pref("hemlock",$new2,"witchgarden",$id);
				}
				$new3=$i3-1;
				if ($new3==0){
					clear_module_pref("mandrake","witchgarden",$id);
				}elseif ($new3<>0){
					set_module_pref("mandrake",$new3,"witchgarden",$id);
				}
			}
		}
		if ($ingredient1==4 && $ingredient2==3 && $ingredient3==2 && $ingredient4==1 && $ingredient5==4){
			$mix=1;
			if ($i4<2 || $i1<1 || $i2< 1 || $i3<1){
				output("You have not the necessary ingredients to make this potion");
			}else{
				output("You have created %s",$n11);
				$new=$p11+1;
				set_module_pref($n11,$new,"magicarena",$id);
				$try=get_module_pref("made11")+1;
				if (get_module_pref("level11")==0){
					set_module_pref("level11",1);
				}
				if ($try==20){
					$levela=get_module_pref("level11");
					$level=get_module_pref("level11")+1;
					if ($levela==20){
						output_notl("`n`n");
						output("You have mastered this potion and are at level 50, you cannot advance any further in levels");
					}elseif ($levela<20){
						output_notl("`n`n");
						output("You gain a level to %s in Potion making for this potion",$try);
						set_module_pref("level11",$level);
						clear_module_pref("made11");
					}
				}elseif($try<20){
					output_notl("`n`n");
					output("You have created %s of this potion",$try);
					set_module_pref("made11",$try);
				}
				$new4=$i4-2;
				if ($new4==0){
					clear_module_pref("scale","witchgarden",$id);
				}elseif ($new0<>0){
					set_module_pref("scale",$new4,"witchgarden",$id);
				}
				$new1=$i1-1;
				if ($new1==0){
					clear_module_pref("venom","witchgarden",$id);
				}elseif ($new1<>0){
					set_module_pref("venom",$new1,"witchgarden",$id);
				}
				$new2=$i2-1;
				if ($new2==0){
					clear_module_pref("hemlock","witchgarden",$id);
				}elseif ($new2<>0){
					set_module_pref("hemlock",$new2,"witchgarden",$id);
				}
				$new3=$i3-1;
				if ($new3==0){
					clear_module_pref("mandrake","witchgarden",$id);
				}elseif ($new3<>0){
					set_module_pref("mandrake",$new3,"witchgarden",$id);
				}
			}
		}
		if ($ingredient1==0 && $ingredient2==0 && $ingredient3==0 && $ingredient4==1 && $ingredient5==4){
			$mix=1;
			if ($i0<3 || $i1<1 || $i4< 1){
				output("You have not the necessary ingredients to make this potion");
			}else{
				output("You have created %s",$n12);
				$new=$p12+1;
				set_module_pref($n12,$new,"magicarena",$id);
				if (get_module_pref("level12")==0){
					set_module_pref("level12",1);
				}
				$try=get_module_pref("made12")+1;
				if ($try==20){
					$levela=get_module_pref("level12");
					$level=get_module_pref("level12")+1;
					if ($levela==20){
						output_notl("`n`n");
						output("You have mastered this potion and are at level 50, you cannot advance any further in levels");
					}elseif ($levela<20){
						output_notl("`n`n");
						output("You gain a level to %s in Potion making for this potion",$try);
						set_module_pref("level12",$level);
						clear_module_pref("made12");
					}
				}elseif($try<20){
					output_notl("`n`n");
					output("You have created %s of this potion",$try);
					set_module_pref("made12",$try);
				}
				$new0=$i0-3;
				if ($new0==0){
					clear_module_pref("monkhood","witchgarden",$id);
				}elseif ($new0<>0){
					set_module_pref("monkhood",$new0,"witchgarden",$id);
				}
				$new1=$i1-1;
				if ($new1==0){
					clear_module_pref("venom","witchgarden",$id);
				}elseif ($new1<>0){
					set_module_pref("venom",$new1,"witchgarden",$id);
				}
				$new4=$i4-1;
				if ($new4==0){
					clear_module_pref("scale","witchgarden",$id);
				}elseif ($new4<>0){
					set_module_pref("scale",$new4,"witchgarden",$id);
				}
			}
		}
		if ($ingredient1==2 && $ingredient2==1 && $ingredient3==3 && $ingredient4==3 && $ingredient5==2){
			$mix=1;
			if ( $i1<1 || $i2< 2 || $i3<2){
				output("You have not the necessary ingredients to make this potion");
			}else{
				output("You have created %s",$n13);
				$new=$p13+1;
				set_module_pref($n13,$new,"magicarena",$id);
				if (get_module_pref("level13")==0){
					set_module_pref("level13",1);
				}
				$try=get_module_pref("made13")+1;
				if ($try==20){
					$levela=get_module_pref("level13");
					$level=get_module_pref("level13")+1;
					if ($levela==20){
						output_notl("`n`n");
						output("You have mastered this potion and are at level 50, you cannot advance any further in levels");
					}elseif ($levela<20){
						output_notl("`n`n");
						output("You gain a level to %s in Potion making for this potion",$try);
						set_module_pref("level13",$level);
						clear_module_pref("made13");
					}
				}elseif($try<20){
					output_notl("`n`n");
					output("You have created %s of this potion",$try);
					set_module_pref("made13",$try);
				}
				$new1=$i1-1;
				if ($new1==0){
					clear_module_pref("venom","witchgarden",$id);
				}elseif ($new1<>0){
					set_module_pref("venom",$new1,"witchgarden",$id);
				}
				$new2=$i2-2;
				if ($new2==0){
					clear_module_pref("hemlock","witchgarden",$id);
				}elseif ($new2<>0){
					set_module_pref("hemlock",$new2,"witchgarden",$id);
				}
				$new3=$i3-2;
				if ($new3==0){
					clear_module_pref("mandrake","witchgarden",$id);
				}elseif ($new3<>0){
					set_module_pref("mandrake",$new3,"witchgarden",$id);
				}
			}
		}
		if ($ingredient1==3 && $ingredient2==4 && $ingredient3==2 && $ingredient4==0 && $ingredient5==2){
			$mix=1;
			if ($i0<1 ||  $i2< 2 || $i3<1 || $i4<1){
				output("You have not the necessary ingredients to make this potion");
			}else{
				output("You have created %s",$n14);
				if (get_module_pref("level14")==0){
					set_module_pref("level14",1);
				}
				$new=$p14+1;
				set_module_pref($n14,$new,"magicarena",$id);
				$try=get_module_pref("made14")+1;
				if ($try==20){
					$levela=get_module_pref("level14");
					$level=get_module_pref("level14")+1;
					if ($levela==20){
						output_notl("`n`n");
						output("You have mastered this potion and are at level 50, you cannot advance any further in levels");
					}elseif ($levela<20){
						output_notl("`n`n");
						output("You gain a level to %s in Potion making for this potion",$try);
						set_module_pref("level14",$level);
						clear_module_pref("made14");
					}
				}elseif($try<20){
					output_notl("`n`n");
					output("You have created %s of this potion",$try);
					set_module_pref("made14",$try);
				}
				$new0=$i0-1;
				if ($new0==0){
					clear_module_pref("monkhood","witchgarden",$id);
				}elseif ($new0<>0){
					set_module_pref("monkhood",$new0,"witchgarden",$id);
				}
				$new4=$i4-1;
				if ($new4==0){
					clear_module_pref("scale","witchgarden",$id);
				}elseif ($new4<>0){
					set_module_pref("scale",$new4,"witchgarden",$id);
				}
				$new2=$i2-2;
				if ($new2==0){
					clear_module_pref("hemlock","witchgarden",$id);
				}elseif ($new2<>0){
					set_module_pref("hemlock",$new2,"witchgarden",$id);
				}
				$new3=$i3-1;
				if ($new3==0){
					clear_module_pref("mandrake","witchgarden",$id);
				}elseif ($new3<>0){
					set_module_pref("mandrake",$new3,"witchgarden",$id);
				}
			}

		} elseif ($mix==-1) {
			$t1=0;
			$t2=0;
			$t3=0;
			$t4=0;
			$t5=0;
			if ($ingredient1==0){
				$t1=$t1+1;
			}elseif ($ingredient1==1){
				$t2=$t2+1;
			}elseif ($ingredient1==2){
				$t3=$t3+1;
			}elseif ($ingredient1==3){
				$t4=$t4+1;
			}elseif ($ingredient1==4){
				$t5=$t5+1;
			}
			if ($ingredient2==0){
				$t1=$t1+1;
			}elseif ($ingredient2==1){
				$t2=$t2+1;
			}elseif ($ingredient2==2){
				$t3=$t3+1;
			}elseif ($ingredient2==3){
				$t4=$t4+1;
			}elseif ($ingredient2==4){
				$t5=$t5+1;
			}
			if ($ingredient3==0){
				$t1=$t1+1;
			}elseif ($ingredient3==1){
				$t2=$t2+1;
			}elseif ($ingredient3==2){
				$t3=$t3+1;
			}elseif ($ingredient3==3){
				$t4=$t4+1;
			}elseif ($ingredient3==4){
				$t5=$t5+1;
			}
			if ($ingredient4==0){
				$t1=$t1+1;
			}elseif ($ingredient4==1){
				$t2=$t2+1;
			}elseif ($ingredient4==2){
				$t3=$t3+1;
			}elseif ($ingredient4==3){
				$t4=$t4+1;
			}elseif ($ingredient4==4){
				$t5=$t5+1;
			}
			if ($ingredient5==0){
				$t1=$t1+1;
			}elseif ($ingredient5==1){
				$t2=$t2+1;
			}elseif ($ingredient5==2){
				$t3=$t3+1;
			}elseif ($ingredient5==3){
				$t4=$t4+1;
			}elseif ($ingredient5==4){
				$t5=$t5+1;
			}
			if ($i0<$t1 || $i1<$t2 || $i2<$t3 || $i3<$t4 || $i4<$t5){
			output("You do not have the onhand ingredients to mix, please try again, and remember to check your ingredients list in your information");
			}else{
				output("You have failed to create anything");
				if ($ingredient1==0){
					$new1=$i0-1;
					if ($new1<0){
						$new1=0;
					}
					if($new1==0){
						clear_module_pref("monkhood","witchgarden",$id);
					}elseif ($new1>0){
						set_module_pref("monkhood",$new1,"witchgarden",$id);
					}
				}elseif($ingredient1==1){
					$new1=$i1-1;
					if ($new1<0){
						$new1=0;
					}
					if($new1==0){
						clear_module_pref("venom","witchgarden",$id);
					}elseif ($new1>0){
						set_module_pref("venom",$new1,"witchgarden",$id);
					}
				}elseif($ingredient1==2){
					$new1=$i2-1;
					if ($new1<0){
						$new1=0;
					}
					if($new1==0){
						clear_module_pref("hemlock","witchgarden",$id);
					}elseif ($new1>0){
						set_module_pref("hemlock",$new1,"witchgarden",$id);
					}
					}elseif($ingredient1==3){
						$new1=$i3-1;
						if ($new1<0){
							$new1=0;
					}
					if($new1==0){
						clear_module_pref("mandrake","witchgarden",$id);
					}elseif ($new1>0){
						set_module_pref("mandrake",$new1,"witchgarden",$id);
					}
				}elseif($ingredient1==4){
					$new1=$i4-1;
					if ($new1<0){
						$new1=0;
					}
					if($new1==0){
						clear_module_pref("scale","witchgarden",$id);
					}elseif ($new1>0){
						set_module_pref("scale",$new1,"witcharden",$id);
					}
				}
				if ($ingredient2==0){
					$new1=$i0-1;
					if ($new1<0){
						$new1=0;
					}
					if($new1==0){
						clear_module_pref("monkhood","witchgarden",$id);
					}elseif ($new1>0){
						set_module_pref("monkhood",$new1,"witchgarden",$id);
					}
				}elseif($ingredient2==1){
						$new1=$i1-1;
						if ($new1<0){
							$new1=0;
					}
					if($new1==0){
						clear_module_pref("venom","witchgarden",$id);
					}elseif ($new1>0){
						set_module_pref("venom",$new1,"witchgarden",$id);
					}
				}elseif($ingredient2==2){
					$new1=$i2-1;
					if ($new1<0){
						$new1=0;
					}
					if($new1==0){
						clear_module_pref("hemlock","witchgarden",$id);
					}elseif ($new1>0){
						set_module_pref("hemlock",$new1,"witchgarden",$id);
					}
				}elseif($ingredient2==3){
					$new1=$i3-1;
					if ($new1<0){
						$new1=0;
					}
					if($new1==0){
						clear_module_pref("mandrake","witchgarden",$id);
					}elseif ($new1>0){
						set_module_pref("mandrake",$new1,"witchgarden",$id);
					}
				}elseif($ingredient2==4){
					$new1=$i4-1;
					if ($new1<0){
						$new1=0;
					}
					if($new1==0){
						clear_module_pref("scale","witchgarden",$id);
					}elseif ($new1>0){
						set_module_pref("scale",$new1,"witcharden",$id);
					}
				}
				if ($ingredient3==0){
					$new1=$i0-1;
					if ($new1<0){
						$new1=0;
					}
					if($new1==0){
						clear_module_pref("monkhood","witchgarden",$id);
					}elseif ($new1>0){
						set_module_pref("monkhood",$new1,"witchgarden",$id);
					}
				}elseif($ingredient3==1){
					$new1=$i1-1;
					if ($new1<0){
						$new1=0;
					}
					if($new1==0){
						clear_module_pref("venom","witchgarden",$id);
					}elseif ($new1>0){
						set_module_pref("venom",$new1,"witchgarden",$id);
					}
				}elseif($ingredient3==2){
					$new1=$i2-1;
					if ($new1<0){
						$new1=0;
					}
					if($new1==0){
						clear_module_pref("hemlock","witchgarden",$id);
					}elseif ($new1>0){
						set_module_pref("hemlock",$new1,"witchgarden",$id);
					}
				}elseif($ingredient3==3){
					$new1=$i3-1;
					if ($new1<0){
						$new1=0;
					}
					if($new1==0){
						clear_module_pref("mandrake","witchgarden",$id);
					}elseif ($new1>0){
						set_module_pref("mandrake",$new1,"witchgarden",$id);
					}
				}elseif($ingredient3==4){
					$new1=$i4-1;
					if ($new1<0){
						$new1=0;
					}
					if($new1==0){
						clear_module_pref("scale","witchgarden",$id);
					}elseif ($new1>0){
						set_module_pref("scale",$new1,"witcharden",$id);
					}
				}
				if ($ingredient4==0){
					$new1=$i0-1;
					if ($new1<0){
					$new1=0;
					}
					if($new1==0){
						clear_module_pref("monkhood","witchgarden",$id);
					}elseif ($new1>0){
						set_module_pref("monkhood",$new1,"witchgarden",$id);
					}
				}elseif($ingredient4==1){
					$new1=$i1-1;
					if ($new1<0){
						$new1=0;
					}
					if($new1==0){
						clear_module_pref("venom","witchgarden",$id);
					}elseif ($new1>0){
						set_module_pref("venom",$new1,"witchgarden",$id);
					}
				}elseif($ingredient4==2){
					$new1=$i2-1;
					if ($new1<0){
						$new1=0;
					}
					if($new1==0){
						clear_module_pref("hemlock","witchgarden",$id);
					}elseif ($new1>0){
						set_module_pref("hemlock",$new1,"witchgarden",$id);
					}
				}elseif($ingredient4==3){
					$new1=$i3-1;
					if ($new1<0){
						$new1=0;
					}
					if($new1==0){
						clear_module_pref("mandrake","witchgarden",$id);
					}elseif ($new1>0){
						set_module_pref("mandrake",$new1,"witchgarden",$id);
					}
				}elseif($ingredient4==4){
					$new1=$i4-1;
					if ($new1<0){
						$new1=0;
					}
					if($new1==0){
						clear_module_pref("scale","witchgarden",$id);
					}elseif ($new1>0){
						set_module_pref("scale",$new1,"witcharden",$id);
					}
				}
				if ($ingredient5==0){
					$new1=$i0-1;
					if ($new1<0){
						$new1=0;
					}
					if($new1==0){
						clear_module_pref("monkhood","witchgarden",$id);
					}elseif ($new1>0){
						set_module_pref("monkhood",$new1,"witchgarden",$id);
					}
				}elseif($ingredient5==1){
					$new1=$i1-1;
					if ($new1<0){
						$new1=0;
					}
					if($new1==0){
						clear_module_pref("venom","witchgarden",$id);
					}elseif ($new1>0){
						set_module_pref("venom",$new1,"witchgarden",$id);
					}
				}elseif($ingredient5==2){
					$new1=$i2-1;
					if ($new1<0){
						$new1=0;
					}
					if($new1==0){
						clear_module_pref("hemlock","witchgarden",$id);
					}elseif ($new1>0){
						set_module_pref("hemlock",$new1,"witchgarden",$id);
					}
				}elseif($ingredient5==3){
					$new1=$i3-1;
					if ($new1<0){
						$new1=0;
					}
					if($new1==0){
						clear_module_pref("mandrake","witchgarden",$id);
					}elseif ($new1>0){
						set_module_pref("mandrake",$new1,"witchgarden",$id);
					}
				}elseif($ingredient5==4){
					$new1=$i4-1;
					if ($new1<0){
						$new1=0;
					}
					if($new1==0){
						clear_module_pref("scale","witchgarden",$id);
					}elseif ($new1>0){
						set_module_pref("scale",$new1,"witcharden",$id);
					}
				}
			}
		}
		addnav("Clan Halls","clan.php");

	}
	if ($op=="register"){
		output("You are now registered in the %s`0 and may receive and send challenges",$aname);
		addnav("Return to Arena","runmodule.php?module=arena&op=magic");
		set_module_pref("mfight",3);
		set_module_pref("magicreg",2,"arena",$id);
		villagenav();
		//now to check and see if they have a gladiatorid, if not assign them one
		$id=$session['user']['acctid'];
		$sql = "SElECT * FROM " . db_prefix("arenastats") . " WHERE id = '$id'";
		$res = db_query($sql);
		$row = db_fetch_assoc($res);
		$gladiator = $row['gladiatorid'];
		if ($gladiator==0){
			$sql = "INSERT INTO " .db_prefix("arenastats") . " (gladiatorid, id) VALUES ('0', '$id')";
			db_query($sql);
		}
	}
	if ($op=="deregister"){
		set_module_pref("magicreg",0,"arena",$id);
		output("You are now Deregistered from the Magic Arena, and may no longer challenge nor receive challenges");
		villagenav();
	}
	if ($op=="challenge"){
		$lev1 = $session['user']['level']-1;
		$lev2 = $session['user']['level']+2;
		$last = date("Y-m-d H:i:s",
				strtotime("-".getsetting("LOGINTIMEOUT", 900)." sec"));
		$loggedin=1;
		$lastip = $session['user']['lastip'];
		$lastid = $session['user']['uniqueid'];
		$acc = db_prefix("accounts");
		$mp = db_prefix("module_userprefs");
		$sqlc = "SELECT $acc.name AS name,
		$acc.acctid AS acctid,
		$mp.value AS registered,
		$mp.userid FROM $mp INNER JOIN $acc
		ON $acc.acctid = $mp.userid
		WHERE $mp.modulename = 'arena'
		AND $mp.setting = 'magicreg'
		AND $mp.userid <> ".$session['user']['acctid']."
		AND $acc.level>=$lev1
		AND $acc.level<=$lev2
		AND $acc.loggedin = $loggedin
		AND $acc.laston>'$last'
		AND $mp.value = 2
		ORDER BY ($mp.value+0)
		";
		$resc = db_query($sqlc);
		$opp = translate_inline("Opponent");
    	$chal = translate_inline("Challenge");
    	$unavailable = translate_inline("Unavailable");
        rawoutput("<table border='0' cellpadding='3' cellspacing='0' align='center'><tr class='trhead'><td style='width:250px' align=center>$opp</td><td align=center>$chal</td></tr>");
        if(!db_num_rows($resc)){
        	$none = translate_inline("None");
        	rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td align=center  colspan=4><i>$none</i></td></tr>");
    	}else{
        	for ($i = 0; $i < db_num_rows($resc); $i++){
        		$rowc = db_fetch_assoc($resc);
	        	$opponent = $rowc['name'];
    	    	$id = $rowc['acctid'];
        		$num = $i+1;
            	rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td>");
	            output_notl($opponent);
    	        rawoutput("</td><td>");
        	   	rawoutput("<a href='runmodule.php?module=magicarena&op=challengesent&opponent=".HTMLEntities($rowc['acctid'])."'>");
        		addnav("","runmodule.php?module=magicarena&op=challengesent&opponent=".HTMLEntities($rowc['acctid']));
        		output_notl("`#[`&Challenge`#]`0");
    		}
    	}
    	rawoutput("</table>");
    	addnav("Return to arena", "runmodule.php?module=arena&op=magic");
    	villagenav();
	}
	if ($op=="challengesent"){
		$id1 = $session['user']['acctid'];
		$hp1 = $session['user']['maxhitpoints'];
		$atk1 = $session['user']['attack'];
		$def1 = $session['user']['defense'];
		$name1=$session['user']['name'];
		//$dk = $session['user']['dragonkills'];
		//$dks=$dk*50;
		//if ($dks<$hp1){
		//	$hp1 = $dks*50;
		//}
		//if ($dks==0){
		//	$hp1=150;
		//}
		//if ($dks>$hp1){
		//	$hp1=$hp1;
		//}
		$lvl=$session['user']['level'];
		$id2=httpget('opponent');
		$sql = "SELECT * FROM " . db_prefix("accounts") . " WHERE acctid = '$id2'";
		$res=db_query($sql);
        $row=db_fetch_assoc($res);
		$hp2 = $row['maxhitpoints'];
		$atk2= $row['attack'];
		$def2 = $row['defense'];
		//$dka = $row['dragonkills'];
		//$dk2 = $dka*50;
		//if ($dk2*50<$hp2){
		//	$hp2 = $dk2*50;
		//}
		//if ($dk2*50==0){
		//	$hp2=150;
		//}
		//if ($dk2*50>$hp2){
		//	$hp2=$hp2;
		//}
		$opponent = $row['name'];
		$sqlb = "INSERT INTO ".db_prefix("arena")." (battleid, type, lvl, id1, name1, id2, name2, hp1, hp2, atk1, atk2, def1, def2) VALUES (0, 2, '$lvl', '$id1', '$name1', '$id2', '$opponent', '$hp1', '$hp2', '$atk1', '$atk2', '$def1', '$def2')";
		db_query($sqlb);
		$sqlq = "SELECT * FROM " .db_prefix("arena"). " WHERE id1 = '$id1' ORDER BY 'battleid' DESC Limit 1";
		$resq = db_query($sqlq);
		$rowq = db_fetch_assoc($resq);
		$battleid = $rowq['battleid'];
		set_module_pref("battleid",$battleid,"arena",$id2);
		set_module_pref("battleid",$battleid,"arena",$id1);
		set_module_pref("mbattleid",$battleid,"arena",$id1);
		set_module_pref("mbattleid",$battleid,"arena",$id2);
		set_module_pref("magicreg",1,"arena",$id2);
		set_module_pref("magicreg",1,"arena",$id1);
		set_module_pref("mfight",4,"arena",$id1);
		set_module_pref("mfight",3,"arena",$id2);
		$min = date("Y-m-d H:i:s");
		set_module_pref("mmin",$min,"arena",$id1);
		set_module_pref("mmin",$min,"arena",$id2);
		output("Your opponent %s`0 has been challenged",$opponent);
		addnav("Continue", "runmodule.php?module=magicarena&op=opponent");
		require_once("lib/systemmail.php");
		systemmail($id2,"`^You Have Been Challenged!`0",array("`&%s`& has challenged you to a %s`& Battle, Please return to the Village to Accept or Decline this challenge.",$session['user']['name'],$aname));
	}
	if ($op=="opponent"){

		$id = $session['user']['acctid'];
		$fight = get_module_pref("mfight","arena",$id);
		$battleid = get_module_pref("mbattleid","arena",$id);
		$sql = "SELECT * FROM " . db_prefix ("arena") . " WHERE battleid = '$battleid'";
		$res = db_query($sql);
		$row = db_fetch_assoc($res);
		$id2 = $row['id2'];
		$timenow = date("Y-m-d H:i:s");
		$time = date("Y-m-d H:i:s",strtotime("-180 seconds"));
		$timeold = get_module_pref("mmin","arena",$id2);
		if ($time>$timeold){
			output_notl("`n`n");
			output("`b`QYour opponent has failed to reply, the battle is cancelled`b");
			villagenav();
			blocknav("runmodule.php?module=magicarena&op=opponent");
			set_module_pref("magicreg",2,"arena",$id2);
			set_module_pref("magicreg",2,"arena",$id);
			set_module_pref("mfight",3,"arena",$id2);
			set_module_pref("mfight",3,"arena",$id);
			set_module_pref("mcancelled",1,"arena",$id2);
			require_once("lib/systemmail.php");
			systemmail($id2,"`^Challenge Cancelled!`0",array("`The battle with %s`^ has been cancelled as you didn't respond in time.",$session['user']['name']));
		}
		if ($time<=$timeold){
			if ($fight==4){
				output("You wait for your opponent to accept or decline the battle");
				addnav("Refresh","runmodule.php?module=magicarena&op=opponent");
			}
			if ($fight==3){
				output("Your opponent has declined the challenge");
				set_module_pref("magicreg",2,"arena",$id);
				villagenav();
			}
			if ($fight==1 || $fight==2 || $fight==0){
				output("Your opponent has accepted, go to the arena");
				addnav("Prepare for Battle","runmodule.php?module=magicarena&op=magicfight&battle=$battleid");
			}
			set_module_pref("min",$timenow);
		}
	}
	if ($op=="magicfight"){
		$id = $session['user']['acctid'];
		$battleid = get_module_pref("mbattleid","arena",$id);
		$sql= "SELECT * FROM " . db_prefix("arena") . " WHERE battleid = '$battleid'";
		$res = db_query($sql);
		$row = db_fetch_assoc($res);
		$id1=$row['id1'];
		$hp = $row['hp1'];
		$atk = $row['atk1'];
		$def = $row['def1'];
		$ophp = $row['hp2'];
		$opatk= $row['atk2'];
		$opdef = $row['def2'];
		$id2=$row['id2'];
		$lvl = $row['lvl'];
		$name1=$row['name1'];
		$name2=$row['name2'];
		$fight = get_module_pref("mfight","arena",$id);

		if (get_module_pref("mfight","arena",$id1)==1 && get_module_pref("mfight","arena",$id2)==1){
			set_module_pref("mfight",2,"arena",$id1);
		}
		if ($id == $row['id1']){
			$potion=get_module_pref("lastpotion","magicarena",$id2);
			$time = date("Y-m-d H:i:s",strtotime("-180 seconds"));
			$timeold = get_module_pref("mmin","arena",$id2);
			$timenow = date("Y-m-d H:i:s");
			$lasthit = get_module_pref("mlasthit","arena",$id2);
			$bonushit = get_module_pref("mbonushit","arena",$id2);
			if (get_module_pref("mtimeout","arena",$id1)==1){
				output_notl("`n`n");
				output("`b`QYou have timed out, your opponent has been awarded the win, the battle is cancelled.`b");
				villagenav();
				set_module_pref("magicreg",2,"arena",$id1);
				blocknav("runmodule.php?module=magicarena&op=magicfight&battle=$battleid");
				blocknav("runmodule.php?module=magicarena&op=magichit&battle=$battleid");
				$sqlr="SELECT * FROM " . db_prefix("arenastats") . " WHERE id = $id1";
				$resr = db_query($sqlr);
				$rowr = db_fetch_assoc($resr);
				$loss = $rowr['magicloss']+=1;
				set_module_pref("mfight",3,"arena",$id1);
				db_query("UPDATE " . db_prefix("arenastats") . " SET magicloss = $loss WHERE id = $id1");
			}
			if (get_module_pref("mtimeout","arena",$id1)==0){
				if ($time>$timeold){
					output_notl("`n`n");
					output("`QYour opponent has timed out, you have been awarded the win, the battle is cancelled`b");
					set_module_pref("magicreg",2,"arena",$id1);
					villagenav();
					blocknav("runmodule.php?module=magicarena&op=magicfight&battle=$battleid");
					blocknav("runmodule.php?module=magicarena&op=magichit&battle=$battleid");
					set_module_pref("mtimeout",1,"arena",$id2);
					$sqlr="SELECT * FROM " . db_prefix("arenastats") . " WHERE id = $id1";
					$resr = db_query($sqlr);
					$rowr = db_fetch_assoc($resr);
					$wins = $rowr['magicwins']+=1;
					set_module_pref("mfight",3,"arena",$id1);
					db_query("UPDATE " . db_prefix("arenastats") . " SET magicwins = $wins WHERE id = $id1");
				}
				if ($time<=$timeold){
					set_module_pref("mmin",$timenow,"arena",$id1);
					if ($hp<=0){
						if ($lasthit>0 && $bonushit==0){
							output("Your opponent hits you for %s damage",$lasthit);
							output("`n`n");
						}
						if ($lasthit>0 && $bonushit>0){
							output("Your opponent hits you for %s damage, and ducking under your guard they execute a second attack for %s damage",$lasthit,$bonushit);
							output("`n`n");
						}
						if ($lasthit<=0 && $bonushit>0){
							output("Your opponent swings and misses, they manage to recover just in time to hit you for %s damage",$bonus);
							output("`n`n");
						}
						output("You have been defeated");
						addnav("Return to Village","village.php");
						$sqlr="SELECT * FROM " . db_prefix("arenastats") . " WHERE id = $id1";
						set_module_pref("magicreg",2,"arena",$id1);
						$resr = db_query($sqlr);
						$rowr = db_fetch_assoc($resr);
						$loss = $rowr['magicloss']+=1;
						set_module_pref("mfight",3,"arena",$id1);
						db_query("UPDATE " . db_prefix("arenastats") . " SET magicloss = $loss WHERE id = $id1");
						blocknav("runmodule.php?module=magicarena&op=magicfight&battle=$battleid");
						blocknav("runmodule.php?module=magicarena&op=magichit&battle=$battleid");
					}
					if ($ophp<=0){
						output("You have won, and earnt yourself a arena point for this arena");
						addnav("Return to Village","village.php");
						set_module_pref("magicreg",2,"arena",$id1);
						$sqlr="SELECT * FROM " . db_prefix("arenastats") . " WHERE id = $id1";
						$resr = db_query($sqlr);
						$rowr = db_fetch_assoc($resr);
						$wins = $rowr['mgaicwins']+=1;
						set_module_pref("mfight",3,"arena",$id1);
						addnews("%s`2 defeated %s `2 in the `3Magical `#Battlegrounds",$name1,$name2);
						db_query("UPDATE " . db_prefix("arenastats") . " SET magicwins = $wins WHERE id = $id1");
						blocknav("runmodule.php?module=magicarena&op=magicfight&battle=$battleid");
						blocknav("runmodule.php?module=magicarena&op=magichit&battle=$battleid");
					}
					if ($ophp>0 && $hp>0){
						$sqla = "SELECT * FROM " . db_prefix("accounts") . " WHERE acctid = '$id2'";
						$resa=db_query($sqla);
						$rowa=db_fetch_assoc($resa);
						if (get_module_pref("mcancelled","arena",$id1)==1){
							output("For some reason, usually a time out the battle was cancelled.");
							set_module_pref("magicreg",2,"arena",$id1);
							set_module_pref("mfight",3,"arena",$id1);
							blocknav("runmodule.php?module=magicarena&op=magicfight&battle=$battleid");
							blocknav("runmodule.php?module=magicarena&op=magichit&battle=$battleid");
							villagenav();
						}
						if (get_module_pref("mcancelled","arena",$id1)==0){
							if ($fight==0){
								output("You enter the arena, to meet your opponent.");
								addnav("Fight", "runmodule.php?module=magicarena&op=magichit&battle=$battleid");
								set_module_pref("mfight",1,"arena",$id2);
								if (get_module_pref("Vires","magicarena",$id1)<>0){
									addnav("Vires","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=1");
								}
								if (get_module_pref("ValdeVires","magicarena",$id1) <> 0){
									addnav("Valde Vires","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=2");
								}
								if (get_module_pref("Tutaminis","magicarena",$id1)<>0){
									addnav("Tutaminis","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=3");
								}
								if (get_module_pref("Navitas","magicarena",$id1)<>0){
									addnav("Navitas","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=4");
								}
								if (get_module_pref("ConfutoNavitas","magicarena",$id1)<>0){
									addnav("Confuto Navitas","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=5");
								}
								if (get_module_pref("Navi","magicarena",$id1)<>0){
									addnav("Navi","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=6");
								}
								if (get_module_pref("Diligo","magicarena",$id1)<>0){
									addnav("Diligo","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=7");
								}
								if (get_module_pref("Abominor","magicarena",$id1)<>0){
									addnav("Abominor","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=8");
								}
								if (get_module_pref("Fragilitas","magicarena",$id1)<>0){
									addnav("Fragilitas","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=9");
								}
								if (get_module_pref("ParumNex","magicarena",$id1)<>0){
									addnav("Parum Nex","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=10");
								}
								if (get_module_pref("PropinquusutNex","magicarena",$id1)<>0){
									addnav("Propinquus ut Nex","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=11");
								}
								if (get_module_pref("LevoutSublimitas","magicarena",$id1)<>0){
									addnav("Levout Sublimitas","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=12");
								}
								if (get_module_pref("Umbra","magicarena",$id1)<>0){
									addnav("Umbra","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=13");
								}
								if (get_module_pref("FlammaofAbyssus","magicarena",$id1)<>0){
									addnav("Flamma of Abyssus","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=14");
								}
							}
							if ($fight==1){
								output("You wait for your opponent to do something.");
								addnav("Refresh","runmodule.php?module=magicarena&op=magicfight&battle=$battleid");
							}
							if ($fight==2){
								if ($potion==0){
									output("Luckily your opponent chose not to use a Potion against you");
									output_notl("`n`n");
								}
								if ($potion==1){
									output("Your opponent increases their strength, hitting you harder");
									output_notl("`n`n");
								}
								if ($potion==2){
									output("Your opponent takes a large draft of a strength potion, hitting you a lot harder");
									output_notl("`n`n");
								}
								if ($potion==3){
									output("Your opponent takes a defence potion from their pack and drinks it, increasing their defence");
									output_notl("`n`n");
								}
								if ($potion==4){
									output("Your opponent takes a glowing potion from their pack, drinking it you watch their lifeforce increase before your eyes");
									output_notl("`n`n");
								}
								if ($potion==5){
									output("Your opponent takes a gold flecked potion from their pack, swallowing it in one gulp, you watch their lifeforce increase dramatically");
									output_notl("`n`n");
								}
								if ($potion==6){
									output("Pulling a strangely colored mixture from their pack, your eye's widen as your opponent suddenly take to the air, making them rather hard to hit");
									output_notl("`n`n");
								}
								if ($potion==7){
									output("Your opponent pulls a pinkish liquid from their pack, before you know whats happened, they've thrown the potion at you, you love your opponent, in a love-crazed daze, you just cannot bring yourself to do anything");
									output_notl("`n`n");
								}
								if ($potion==8){
									if (get_module_pref("love","magicarena",$id2)<>0){
										output("Your opponent smears a blackish paste onto their skin, negating the effects of the love potion somewhat");
									}
									if (get_module_pref("love","magicarena",$id2)==0){
										output("Your opponent smears a blackish paste onto their skin, totally making the Diligo you used ineffective");
									}
									output_notl("`n`n");
								}
								if ($potion==9){
									output("Blinking you are enveloped in a misty cloud, you feel yourself weakening");
									output_notl("`n`n");
								}
								if ($potion==10){
									output("A stabbing pain makes you look down to see a greenish mixture adhered to your body, you feel your life force ebb somewhat");
									output_notl("`n`n");
								}
								if ($potion==11){
									output("Your opponent pulls a sparkling silver vial from their pack, suddenly they grab you and force you to drink it, zapping some of your life");
									output_notl("`n`n");
								}
								if ($potion==12){
									output("Your opponent, takes a rosy flask from their pack, taking a hefty draught they toss the empty flask to the side, they appear much larger to you");
								}
								if ($potion==13){
									output("Sipping on a shadowy mixture, your opponent seems to blend into the shadows enshrouding this arena, they attack you whilst you struggle to locate them");
									output_notl("`n`n");
								}
								if ($potion==14){
									output("Taking a rainbow colored glass vial from their pack, your opponent smashes it on the ground, robbing you of some vital stats");
									output_notl("`n`n");
								}
								if ($lasthit>0 && $bonushit==0){
									output("Your opponent hits you for %s damage",$lasthit);
									output_notl("`n`n");
								}
								if ($lasthit>0 && $bonushit>0){
									output("Your opponent hits you for %s damage, and ducking under your guard they execute a second attack for %s damage",$lasthit,$bonushit);
									output_notl("`n`n");
								}
								if ($lasthit<=0 && $bonushit>0){
									output("Your opponent swings and misses, they manage to recover just in time to hit you for %s damage",$bonus);
									output_notl("`n`n");
								}
								if ($lasthit==0 && $bonushit==0){
									output("Your opponent misses");
									output_notl("`n`n");
								}
								if ($lasthit<0 && $bonushit==0){
									output("You fend off your opponents attack, riposting for %s damage",$lasthit);
									output_notl("`n`n");
								}
								if ($lasthit<0 && $bonushit<>0){
									output("You fend off your opponents attack, riposting for %s damage, they manage to recover enough to hit you for %s damage",$lasthit,$bonushit);
									output_notl("`n`n");
								}
								output("It is now your turn.");
								if (get_module_pref("Vires","magicarena",$id1)<>0){
									addnav("Vires","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=1");
								}
								if (get_module_pref("ValdeVires","magicarena",$id1) <> 0){
									addnav("Valde Vires","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=2");
								}
								if (get_module_pref("Tutaminis","magicarena",$id1)<>0){
									addnav("Tutaminis","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=3");
								}
								if (get_module_pref("Navitas","magicarena",$id1)<>0){
									addnav("Navitas","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=4");
								}
								if (get_module_pref("ConfutoNavitas","magicarena",$id1)<>0){
									addnav("Confuto Navitas","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=5");
								}
								if (get_module_pref("Navi","magicarena",$id1)<>0){
									addnav("Navi","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=6");
								}
								if (get_module_pref("Diligo","magicarena",$id1)<>0){
									addnav("Diligo","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=7");
								}
								if (get_module_pref("Abominor","magicarena",$id1)<>0){
									addnav("Abominor","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=8");
								}
								if (get_module_pref("Fragilitas","magicarena",$id1)<>0){
									addnav("Fragilitas","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=9");
								}
								if (get_module_pref("ParumNex","magicarena",$id1)<>0){
									addnav("Parum Nex","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=10");
								}
								if (get_module_pref("PropinquusutNex","magicarena",$id1)<>0){
									addnav("Propinquus ut Nex","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=11");
								}
								if (get_module_pref("LevoutSublimitas","magicarena",$id1)<>0){
									addnav("Levout Sublimitas","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=12");
								}
								if (get_module_pref("Umbra","magicarena",$id1)<>0){
									addnav("Umbra","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=13");
								}
								if (get_module_pref("FlammaofAbyssus","magicarena",$id1)<>0){
									addnav("Flamma of Abyssus","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=14");
								}
								addnav("Fight","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=0");
							}
						}
					}
				}
			}
			output_notl("`n`n");
			output("`b`c`^Arena Stats`b");
			output_notl("`n`n");
			output("Your Stats");
			output_notl("`n`n");
			output("`@Hitpoints: `#%s",$hp);
			output_notl("`n`n");
			output("`@Attack: `#%s",$atk);
			output_notl("`n`n");
			output("`@Defense: `#%s",$def);
			output_notl("`n`n");
			output("`b`c`^Arena Stats`b");
			output_notl("`n`n");
			output("Opponents Stats");
			output_notl("`n`n");
			output("`@Hitpoints: `#%s",$ophp);
			output_notl("`n`n");
			output("`@Attack: `#%s",$opatk);
			output_notl("`n`n");
			output("`@Defense: `#%s",$opdef);
		}
		if ($id == $row['id2']){
			$potion=get_module_pref("lastpotion","magicarena",$id1);
			$time = date("Y-m-d H:i:s",strtotime("-180 seconds"));
			$timeold = get_module_pref("mmin","arena",$id1);
			$timenow = date("Y-m-d H:i:s");
			$lasthit = get_module_pref("mlasthit","arena",$id1);
			$bonushit = get_module_pref("mbonushit","arena",$id1);
			if (get_module_pref("mtimeout","arena",$id2)==1){
				output_notl("`n`n");
				output("`b`QYou have timed out, your opponent has been awarded the win, the battle is cancelled.`b");
				set_module_pref("magicreg",2,"arena",$id2);
				villagenav();
				blocknav("runmodule.php?module=magicarena&op=magicfight&battle=$battleid");
				blocknav("runmodule.php?module=magicarena&op=magichit&battle=$battleid");
				$sqlr="SELECT * FROM " . db_prefix("arenastats") . " WHERE id = $id2";
				$resr = db_query($sqlr);
				$rowr = db_fetch_assoc($resr);
				$loss = $rowr['magicloss']+=1;
				set_module_pref("mfight",3,"arena",$id2);
				db_query("UPDATE " . db_prefix("arenastats") . " SET magicloss = $loss WHERE id = $id2");
			}
			if (get_module_pref("mtimeout","arena",$id2)==0){
				if ($time>$timeold){
					output_notl("`n`n");
					output("`b`QYour opponent has timed out, you have been awarded the win, the battle is cancelled`b");
					set_module_pref("magicreg",2,"arena",$id2);
					villagenav();
					blocknav("runmodule.php?module=magicarena&op=magicfight&battle=$battleid");
					blocknav("runmodule.php?module=magicarena&op=magichit&battle=$battleid");
					set_module_pref("mtimeout",1,"arena",$id1);
					$sqlr="SELECT * FROM " . db_prefix("arenastats") . " WHERE id = $id2";
					$resr = db_query($sqlr);
					$rowr = db_fetch_assoc($resr);
					$wins = $rowr['magicwins']+=1;
					set_module_pref("mfight",3,"arena",$id2);
					db_query("UPDATE " . db_prefix("arenastats") . " SET magicwins = $wins WHERE id = $id2");
				}
				if ($time<=$timeold){
					set_module_pref("mmin",$timenow,"arena",$id2);
					if ($ophp<=0){
						if ($lasthit>0 && $bonushit==0){
							output("Your opponent hits you for %s damage",$lasthit);
							output("`n`n");
						}
						if ($lasthit>0 && $bonushit>0){
							output("Your opponent hits you for %s damage, and ducking under your guard they execute a second attack for %s damage",$lasthit,$bonushit);
							output("`n`n");
						}
						if ($lasthit<=0 && $bonushit>0){
							output("Your opponent swings and misses, they manage to recover just in time to hit you for %s damage",$bonus);
							output("`n`n");
						}
						output("You have been defeated");
						addnav("Return to Village","village.php");
						blocknav("runmodule.php?module=magicarena&op=magicfight&battle=$battleid");
						blocknav("runmodule.php?module=magicarena&op=magichit&battle=$battleid");
						set_module_pref("magicreg",2,"arena",$id2);
						$sqlr="SELECT * FROM " . db_prefix("arenastats") . " WHERE id = $id2";
						$resr = db_query($sqlr);
						$rowr = db_fetch_assoc($resr);
						$loss = $rowr['magicloss']+=1;
						set_module_pref("mfight",3,"arena",$id2);
						db_query("UPDATE " . db_prefix("arenastats") . " SET magicloss = $loss WHERE id = $id2");
					}
					if ($hp<=0){
						output("You have won, and earnt yourself a arena point for this arena");
						addnav("Return to Village","village.php");
						$sqlr="SELECT * FROM " . db_prefix("arenastats") . " WHERE id = $id2";
						$resr = db_query($sqlr);
						$rowr = db_fetch_assoc($resr);
						$wins = $rowr['magicwins']+=1;
						set_module_pref("mfight",3,"arena",$id2);
						set_module_pref("magicreg",2,"arena",$id2);
						addnews("%s`2 defeated %s `2 in the`3 Magical `#Battlegrounds",$name2,$name1);
						db_query("UPDATE " . db_prefix("arenastats") . " SET magicwins = $wins WHERE id = $id2");
						blocknav("runmodule.php?module=magicarena&op=magicfight&battle=$battleid");
						blocknav("runmodule.php?module=magicarena&op=magichit&battle=$battleid");
					}
					if ($ophp>0 && $hp>0){
						$sqla = "SELECT * FROM " . db_prefix("accounts") . " WHERE acctid = '$id1'";
						$resa=db_query($sqla);
						$rowa=db_fetch_assoc($resa);
						if (get_module_pref("mcancelled","arena",$id2)==1){
							output("For some reason, usually a time out the battle was cancelled.");
							blocknav("runmodule.php?module=magicarena&op=magicfight&battle=$battleid");
							blocknav("runmodule.php?module=magicarena&op=magichit&battle=$battleid");
							set_module_pref("magicreg",2,"arena",$id2);
							set_module_pref("mfight",3,"arena",$id2);
							villagenav();
						}
						if (get_module_pref("mcancelled","arena",$id2)==0){
							if ($fight==0){
							output("You enter the arena, to meet your opponent.");
							set_module_pref("mfight",1,"arena",$id1);
							addnav("Fight","runmodule.php?module=magicarena&op=magichit&battle=$battleid");
							if (get_module_pref("Vires","magicarena",$id2)<>0){
									addnav("Vires","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=1");
								}
								if (get_module_pref("ValdeVires","magicarena",$id2) <> 0){
									addnav("Valde Vires","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=2");
								}
								if (get_module_pref("Tutaminis","magicarena",$id2)<>0){
									addnav("Tutaminis","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=3");
								}
								if (get_module_pref("Navitas","magicarena",$id2)<>0){
									addnav("Navitas","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=4");
								}
								if (get_module_pref("ConfutoNavitas","magicarena",$id2)<>0){
									addnav("Confuto Navitas","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=5");
								}
								if (get_module_pref("Navi","magicarena",$id2)<>0){
									addnav("Navi","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=6");
								}
								if (get_module_pref("Diligo","magicarena",$id2)<>0){
									addnav("Diligo","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=7");
								}
								if (get_module_pref("Abominor","magicarena",$id2)<>0){
									addnav("Abominor","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=8");
								}
								if (get_module_pref("Fragilitas","magicarena",$id2)<>0){
									addnav("Fragilitas","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=9");
								}
								if (get_module_pref("ParumNex","magicarena",$id2)<>0){
									addnav("Parum Nex","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=10");
								}
								if (get_module_pref("PropinquusutNex","magicarena",$id2)<>0){
									addnav("Propinquus ut Nex","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=11");
								}
								if (get_module_pref("LevoutSublimitas","magicarena",$id2)<>0){
									addnav("Levout Sublimitas","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=12");
								}
								if (get_module_pref("Umbra","magicarena",$id2)<>0){
									addnav("Umbra","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=13");
								}
								if (get_module_pref("FlammaofAbyssus","magicarena",$id2)<>0){
									addnav("Flamma of Abyssus","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=14");
								}
							}
							if ($fight==1){
								output("You wait for your opponent to do something.");
								addnav("Refresh","runmodule.php?module=magicarena&op=magicfight&battle=$battleid");

							}
							if ($fight==2){
								if ($potion==0){
									output("Luckily your opponent chose not to use a Potion against you");
									output_notl("`n`n");
								}
								if ($potion==1){
									output("Your opponent increases their strength, hitting you harder");
									output_notl("`n`n");
								}
								if ($potion==2){
									output("Your opponent takes a large draft of a strength potion, hitting you a lot harder");
									output_notl("`n`n");
								}
								if ($potion==3){
									output("Your opponent takes a defence potion from their pack and drinks it, increasing their defence");
									output_notl("`n`n");
								}
								if ($potion==4){
									output("Your opponent takes a glowing potion from their pack, drinking it you watch their lifeforce increase before your eyes");
									output_notl("`n`n");
								}
								if ($potion==5){
									output("Your opponent takes a gold flecked potion from their pack, swallowing it in one gulp, you watch their lifeforce increase dramatically");
									output_notl("`n`n");
								}
								if ($potion==6){
									output("Pulling a strangely colored mixture from their pack, your eye's widen as your opponent suddenly take to the air, making them rather hard to hit");
									output_notl("`n`n");
								}
								if ($potion==7){
									output("Your opponent pulls a pinkish liquid from their pack, before you know whats happened, they've thrown the potion at you, you love your opponent, in a love-crazed daze, you just cannot bring yourself to do anything");
									output_notl("`n`n");
								}
								if ($potion==8){
									if (get_module_pref("love","magicarena",$id1)<>0){
										output("Your opponent smears a blackish paste onto their skin, negating the effects of the love potion somewhat");
									}
									if (get_module_pref("love","magicarena",$id1)==0){
										output("Your opponent smears a blackish paste onto their skin, totally making the Diligo you used ineffective");
									}
									output_notl("`n`n");
								}
								if ($potion==9){
									output("Blinking you are enveloped in a misty cloud, you feel yourself weakening");
									output_notl("`n`n");
								}
								if ($potion==10){
									output("A stabbing pain makes you look down to see a greenish mixture adhered to your body, you feel your life force ebb somewhat");
									output_notl("`n`n");
								}
								if ($potion==11){
									output("Your opponent pulls a sparkling silver vial from their pack, suddenly they grab you and force you to drink it, zapping some of your life");
									output_notl("`n`n");
								}
								if ($potion==12){
									output("Your opponent, takes a rosy flask from their pack, taking a hefty draught they toss the empty flask to the side, they appear much larger to you");
								}
								if ($potion==13){
									output("Sipping on a shadowy mixture, your opponent seems to blend into the shadows enshrouding this arena, they attack you whilst you struggle to locate them");
									output_notl("`n`n");
								}
								if ($potion==14){
									output("Taking a rainbow colored glass vial from their pack, your opponent smashes it on the ground, robbing you of some vital stats");
									output_notl("`n`n");
								}
								if ($lasthit>0 && $bonushit==0){
									output("Your opponent hits you for %s damage",$lasthit);
									output_notl("`n`n");
								}
								if ($lasthit>0 && $bonushit>0){
									output("Your opponent hits you for %s damage, and ducking under your guard they execute a second attack for %s damage",$lasthit,$bonushit);
									output_notl("`n`n");
								}
								if ($lasthit<=0 && $bonushit>0){
									output("Your opponent swings and misses, they manage to recover just in time to hit you for %s damage",$bonus);
									output_notl("`n`n");
								}
								if ($lasthit==0 && $bonushit==0){
									output("Your opponent misses");
									output_notl("`n`n");
								}
								if ($lasthit<0 && $bonushit==0){
									output("You fend off your opponents attack, riposting for %s damage",$lasthit);
									output_notl("`n`n");
								}
								if ($lasthit<0 && $bonushit<>0){
									output("You fend off your opponents attack, riposting for %s damage, they manage to recover enough to hit you for %s damage",$lasthit,$bonushit);
									output_notl("`n`n");
								}
								set_module_pref("lastpotion",0,"magicarena",$id2);
								output("Your opponent has attacked, it is now your turn.");
								if (get_module_pref("Vires","magicarena",$id2)<>0){
									addnav("Vires","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=1");
								}
								if (get_module_pref("ValdeVires","magicarena",$id2) <> 0){
									addnav("Valde Vires","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=2");
								}
								if (get_module_pref("Tutaminis","magicarena",$id2)<>0){
									addnav("Tutaminis","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=3");
								}
								if (get_module_pref("Navitas","magicarena",$id2)<>0){
									addnav("Navitas","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=4");
								}
								if (get_module_pref("ConfutoNavitas","magicarena",$id2)<>0){
									addnav("Confuto Navitas","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=5");
								}
								if (get_module_pref("Navi","magicarena",$id2)<>0){
									addnav("Navi","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=6");
								}
								if (get_module_pref("Diligo","magicarena",$id2)<>0){
									addnav("Diligo","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=7");
								}
								if (get_module_pref("Abominor","magicarena",$id2)<>0){
									addnav("Abominor","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=8");
								}
								if (get_module_pref("Fragilitas","magicarena",$id2)<>0){
									addnav("Fragilitas","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=9");
								}
								if (get_module_pref("ParumNex","magicarena",$id2)<>0){
									addnav("Parum Nex","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=10");
								}
								if (get_module_pref("PropinquusutNex","magicarena",$id2)<>0){
									addnav("Propinquus ut Nex","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=11");
								}
								if (get_module_pref("LevoutSublimitas","magicarena",$id2)<>0){
									addnav("Levout Sublimitas","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=12");
								}
								if (get_module_pref("Umbra","magicarena",$id2)<>0){
									addnav("Umbra","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=13");
								}
								if (get_module_pref("FlammaofAbyssus","magicarena",$id2)<>0){
									addnav("Flamma of Abyssus","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=14");
								}
								addnav("Fight","runmodule.php?module=magicarena&op=magichit&battle=$battleid&potion=0");
							}
						}
					}
				}
			}
			output_notl("`n`n");
			output("`b`c`^Arena Stats`b");
			output_notl("`n`n");
			output("Your Stats");
			output_notl("`n`n");
			output("`@Hitpoints: `#%s",$ophp);
			output_notl("`n`n");
			output("`@Attack: `#%s",$opatk);
			output_notl("`n`n");
			output("`@Defense: `#%s",$opdef);
			output_notl("`n`n");
			output("`b`c`^Arena Stats`b");
			output_notl("`n`n");
			output("Opponents Stats");
			output_notl("`n`n");
			output("`@Hitpoints: `#%s",$hp);
			output_notl("`n`n");
			output("`@Attack: `#%s",$atk);
			output_notl("`n`n");
			output("`@Defense: `#%s",$def);
		}
	}
	if ($op=="book"){
		//taken from XChrisX's questbook.php starting here
		global $session;
		$return = httpget('return');
		$return = cmd_sanitize($return);
		$return = substr($return,strrpos($return,"/")+1);
		tlschema("nav");
		addnav("Return whence you came",$return);
		tlschema();
		$userid = httpget("user");
		$strike = false;
		//taken from xChrisX's questbook.php ending here
		output("`b`c`2P`6o`3t`6i`2o`6n`3s`b`c");
		output_notl("`n`n");
		if ($p1<>0 || get_module_pref("level1")<>0 || get_module_pref("made1")<>0){
			output("`b`%Strength`b");
			output_notl("`n`n");
			output("`7Monkhood,Basilisk Venom, Monkhood, Basilisk Venom and Mandrake to make `b`%Vires`b");
			output_notl("`n`n");
		}
		if ($p2<>0 || get_module_pref("level2")<>0 || get_module_pref("made2")<>0){
			output("`b`#Super Strength`b");
			output_notl("`n`n");
			output("`&Three Monkhoods, Obsidian Dragon Scale and a Mandrake to make `b`#Valde Vires`b");
			output_notl("`n`n");
		}
		if ($p3<>0 || get_module_pref("level3")<>0 || get_module_pref("made3")<>0){
			output("`b`%Defence`b");
			output_notl("`n`n");
			output("`7Basilisk Venom and a Mandrake mix in Monkhood another Mandrake and Basilisk Venom to get `b`%Tutaminis`b");
			output_notl("`n`n");
		}
		if ($p4<>0 || get_module_pref("level4")<>0 || get_module_pref("made4")<>0){
			output("`b`#Energy`b");
			output_notl("`n`n");
			output("`&Mandrake, two Basilisk Venoms, Monkhood and Mandrake to get `b`#Navitas`b");
			output_notl("`n`n");
		}
		if ($p5<>0 || get_module_pref("level5")<>0 || get_module_pref("made5")<>0){
			output("`b`%Supreme Energy`b");
			output_notl("`n`n");
			output("`7Basilisk Venom, Mandrake, Basilisk Venom, Obsidian Dragon Scale and Basilisk Venom to create `b`%Confuto Navitas`b");
			output_notl("`n`n");
		}
		if ($p6<>0 || get_module_pref("level6")<>0 || get_module_pref("made6")<>0){
			output("`b`#Flying`b");
			output_notl("`n`n");
			output("`&Obsidian Dragon Scale, Basilisk Venom, Two Mandrakes, Basilisk Venom to form `b`#Navi`b");
			output_notl("`n`n");
		}
		if ($p7<>0 || get_module_pref("level7")<>0 || get_module_pref("made7")<>0){
			output("`b`%Love`b");
			output_notl("`n`n");
			output("`7Hemlock, Monkhood, Hemlock, Basilisk Venom and Hemlock to see `b`%Diligo`b");
			output_notl("`n`n");
		}
		if ($p8<>0 || get_module_pref("level8")<>0 || get_module_pref("made8")<>0){
			output("`b`#Hate`b");
			output_notl("`n`n");
			output("`&Mandrake, Monkhood,	Mandrake, Basilisk Venom and Mandrake to form `b`#Abominor`b");
			output_notl("`n`n");
		}
		if ($p9<>0 || get_module_pref("level9")<>0 || get_module_pref("made9")<>0){
			output("`b`%Weakness`b");
			output_notl("`n`n");
			output("`7Two Hemlocks, Mandrake, two more Hemlocks to make `b`%Fragilitas`b");
			output_notl("`n`n");
		}
		if ($p10<>0 || get_module_pref("level10")<>0 || get_module_pref("made10")<>0){
			output("`b`#Little Death`b");
			output_notl("`n`n");
			output("`&Monkhood, Mandrake, Hemlock, Basilisk Venom and another Monkhood to create `b`#Parum Nex`b");
			output_notl("`n`n");
		}
		if ($p11<>0 || get_module_pref("level11")<>0 || get_module_pref("made11")<>0){
			output("`b`%Closer to Death`b");
			output_notl("`n`n");
			output("`7Obsidian Dragon Scale, Mandrake, Hemlock, Basilisk Venom and Obsidian Dragon Scale you will have `b`%Propinquus ut Nex`b");
			output_notl("`n`n");
		}
		if ($p12<>0 || get_module_pref("level12")<>0 || get_module_pref("made12")<>0){
			output("`b`#Lift to the Heights`b");
			output_notl("`n`n");
			output("`&Three Monkhoods, Basilisk Venom and Obsidian Dragon Scale to get `b`#Levo ut Sublimitas`b");
			output_notl("`n`n");
		}
		if ($p13<>0 || get_module_pref("level13")<>0 || get_module_pref("made13")<>0){
			output("`b`%Shadows`b");
			output_notl("`n`n");
			output("`7Hemlock, Basilisk Venom, two Mandrakes and another Hemlock to see `b`%Umbra`b");
			output_notl("`n`n");
		}
		if ($p14<>0 || get_module_pref("level14")<>0 || get_module_pref("made14")<>0){
			output("`b`#Flames of Hell`b");
			output_notl("`n`n");
			output("`&Mandrake, Obsidian Dragon Scale, Hemlock, Monkhood add another Hemlock to create `b`#Flamma of Abyssus`b");
		}
	}
	//do the magichits with the using of potions and potion levels
	if ($op=="magichit"){
		$battleid=httpget('battle');
		$potion=httpget('potion');
		//output("potion %s`n`n",$potion);
		$id = $session['user']['acctid'];
		$sql= "SELECT * FROM " . db_prefix("arena") . " WHERE battleid = '$battleid' ORDER BY battleid DESC Limit 1";
		$res = db_query($sql);
		$row = db_fetch_assoc($res);
		$id1 = $row['id1'];
		$hp = $row['hp1'];
		$atk = $row['atk1'];
		$def = $row['def1'];
		$ophp = $row['hp2'];
		$opatk= $row['atk2'];
		$opdef = $row['def2'];
		$name1=$row['name1'];
		$name2=$row['name2'];
		$id2=$row['id2'];
		$lvl = $row['lvl'];


		if ($id == $id1){
			if ($potion==0){
				$opatka=$opatk;
				$opdefa=$opdef;
				$ophpa=$ophp;
				$hpa=$hp;
				$atka=$atk;
				$defa=$def;
			}
			if ($potion==1){
				$new=get_module_pref("Vires","magicarena",$id1)-1;
				if ($new==0){
					clear_module_pref("Vires","magicarena",$id1);
				}elseif ($new>0){
					set_module_pref("Vires",$new,"magicarena",$id1);
				}
				$pot = get_module_pref("level1","magicarena",$id1);
				$a=$pot*3;
				$atka=$atk+$a;
				$opatka=$opatk;
				$opdefa=$opdef;
				$ophpa=$ophp;
				$hpa=$hp;
				$defa=$def;
				output("You drink the Vires increasing your strength, enabling you to hit harder %s",$atka);
				output_notl("`n`n");
			}
			if ($potion==2){
				$new=get_module_pref($n2,"magicarena",$id1)-1;
				//output("n2 = %s, new = %s",$n2,$new);
				if ($new==0){
					clear_module_pref($n2,"magicarena",$id1);
				}elseif($new>0){
					set_module_pref($n2,$new,"magicarena",$id1);
				}
				$pot = get_module_pref("level2","magicarena",$id1);
				$a=$pot*5;
				$atka=$atk+$a;
				$opatka=$opatk;
				$opdefa=$opdef;
				$ophpa=$ophp;
				$hpa=$hp;
				$defa=$def;
				output("You drink the Valde Vires increasing your strength, enabling you to hit harder %s",$atka);
				output_notl("`n`n");
			}
			if ($potion==3){
				$new=get_module_pref($n3,"magicarena",$id1)-1;
				//output("n3 = %s, new = %s",$n3,$new);
				if ($new==0){
					clear_module_pref($n3,"magicarena",$id1);
				}elseif ($new>0){
					set_module_pref($n3,$new,"magicarena",$id1);
				}
				$pot=get_module_pref("level3","magicarena",$id1);
				$a=$pot*3;
				$defa=$def+$a;
				$b=$pot*0.01;
				$c=$hp*$b;
				$hpa = $hp+$c;
				$opatka=$opatk;
				$opdefa=$opdef;
				$ophpa=$ophp;
				$atka=$atk;
				output("You take a Tutaminis potion from your pack and drink it, increasing your defence",$n3);
				output_notl("`n`n");
			}
			if ($potion==4){
				$new=get_module_pref($n4,"magicarena",$id1)-1;
				if($new==0){
					clear_module_pref($n4,"magicarena",$id1);
				}elseif($new>0){
					set_module_pref($n4,$new,"magicarena",$id1);
				}
				$pot=get_module_pref("level4","magicarena",$id1);
				//output("n4 = %s, new = %s",$n4,$new);
				$a=$pot*0.02;
				$b=$hp*$a;
				$hpa=$hp+$b;
				$opatka=$opatk;
				$opdefa=$opdef;
				$ophpa=$ophp;
				$atka=$atk;
				$defa=$def;
				output("You take the glowing potion, Navitas from the pack, drinking it you feel your lifeforce increase.");
				output_notl("`n`n");
			}
			if ($potion==5){
				$new=get_module_pref($n5,"magicarena",$id1)-1;
				if($new==0){
					clear_module_pref($n5,"magicarena",$id1);
				}elseif($new>0){
					set_module_pref($n5,$new,"magicarena",$id1);
				}
				$pot=get_module_pref("level5","magicarena",$id1);
				$a=$pot*0.04;
				$b=$hp*$a;
				$hpa=$hp+$b;
				$opatka=$opatk;
				$opdefa=$opdef;
				$ophpa=$ophp;
				$atka=$atk;
				$defa=$def;
				output("You take a gold flecked Confuto Navitas potion from your pack, swallowing it in one gulp, your lifeforce increases dramatically");
				output_notl("`n`n");
			}
			if ($potion==6){
				$new=get_module_pref($n6,"magicarena",$id1)-1;
				if($new==0){
					clear_module_pref($n6,"magicarena",$id1);
				}elseif($new>0){
					set_module_pref($n6,$new,"magicarena",$id1);
				}
				$pot=get_module_pref($level6,"magicarena",$id1);
				$a=$pot*0.05;
				$b=$def*$a;
				$defa=$def+$b;
				$c=$pot*0.01;
				$d=$ophp*$c;
				$ophpa=$ophp-$d;
				$opatka=$opatk;
				$opdefa=$opdef;
				$hpa=$hp;
				$atka=$atk;
				output("Pulling a strangely colored mixture from your pack, you drink the Navi and suddenly take to the air, making you rather hard to hit");
				output_notl("`n`n");
			}
			if (get_module_pref("love","magicarena",$id1)<>0){
				$atka=0;
				$defa=0;
				$opatka=$opatk;
				$opdefa=$opdef;
				$ophpa=$ophp;
				$hpa=$hp;
			}
			if (get_module_pref("love","magicarena",$id2)<>0){
				$opatka=0;
				$opdefa=0;
				$ophpa=$ophp;
				$hpa=$hp;
				$atka=$atk;
				$defa=$def;
			}
			if ($potion==7){
				$new=get_module_pref($n7,"magicarena",$id1)-1;
				if($new==0){
					clear_module_pref($n7,"magicarena",$id1);
				}elseif($new>0){
					set_module_pref($n7,$new,"magicarena",$id1);
				}
				$pot=get_module_pref($level7,"magicarena",$id1);
				$love=get_module_pref("love","magicarena",$id2);
				$ll=$love-$pot;
				set_module_pref("love",$ll,"magicarena",$id2);
				output("You pull a pinkish liquid from the pack, before your opponent can react you throw the Diligo potion at them, they look at you in a love-crazed daze and appear to be defenceless");
				output_notl("`n`n");
				if ($ll<1){
					$atka=$atk+10;
					$defa=$def+10;
				}
				if ($ll>=1){
					$atka=$atk;
					$defa=$def;
				}
				$opatka=$opatk;
				$opdefa=$opdef;
				$ophpa=$ophp;
				$hpa=$hp;
			}
			if ($potion==8){
				$new=get_module_pref($n8,"magicarena",$id1)-1;
				if($new==0){
					clear_module_pref($n8,"magicarena",$id1);
				}elseif($new>0){
					set_module_pref($n8,$new,"magicarena",$id1);
				}
				$pot=get_module_pref($level8,"magicarena",$id1);
				$ad = get_module_pref("love","magicarena",$id1)-$pot;
				if ($ad<=0){
					clear_module_pref("love","magicarena",$id1);
					output("You smear a blackish paste over your skin, negating the effects of that love potion");
				}
				if ($ad>0){
					set_module_pref("love",$ad,"magicarena",$id1);
					output("You smear a blackish paste over your skin, somewhat negating the effects of that love potion");
				}
				$opatka=$opatk;
				$opdefa=$opdef;
				$ophpa=$ophp;
				$hpa=$hp;
				$atka=$atk;
				$defa=$def;
				output_notl("`n`n");
			}
			if ($potion==9){
				$new=get_module_pref($n9,"magicarena",$id1)-1;
				if($new==0){
					clear_module_pref($n9,"magicarena",$id1);
				}elseif($new>0){
					set_module_pref($n9,$new,"magicarena",$id1);
				}
				$pot=get_module_pref($level9,"magicarena",$id1);
				$a=$pot*2;
				$opatka=$opatk-$a;
				$b=$pot*0.01;
				$c=$ophp*$b;
				$ophpa=$ophp-$c;
				$d=$pot*3;
				$opdefa=$opdef-$d;
				$hpa=$hp;
				$atka=$atk;
				$defa=$def;
				output("Smashing a bottle filled with a misty cloud at their feet, you weaken your opponent");
				output_notl("`n`n");
			}
			if ($potion==10){
				$new=get_module_pref($n10,"magicarena",$id1)-1;
				if($new==0){
					clear_module_pref($n10,"magicarena",$id1);
				}elseif($new>0){
					set_module_pref($n10,$new,"magicarena",$id1);
				}
				$pot=get_module_pref($level10,"magicarena",$id1);
				$a=$pot*0.005;
				$b=$ophp*$a;
				$ophpa=$ophp-$b;
				$opatka=$opatk;
				$opdefa=$opdef;
				$hpa=$hp;
				$atka=$atk;
				$defa=$def;
				output("Pulling out a vial containing a thick greenish mixture you throw it full force at your opponent, drawing some of their lifeforce away");
				output_notl("`n`n");
			}
			if ($potion==11){
				$new=get_module_pref($n11,"magicarena",$id1)-1;
				if($new==0){
					clear_module_pref($n11,"magicarena",$id1);
				}elseif($new>0){
					set_module_pref($n11,$new,"magicarena",$id1);
				}
				$pot=get_module_pref($level11,"magicarena",$id1);
				$a=$pot*0.013;
				$b=$ophp*$a;
				$ophpa=$ophp-$b;
				$opatka=$opatk;
				$opdefa=$opdef;
				$hpa=$hp;
				$atka=$atk;
				$defa=$def;
				output("Your move suddenly, in one motion pulling a sparkling silver vial out, in the next grabbing your opponent and forcing them to drink the mixture, zapping some of their life");
				output_notl("`n`n");
			}
			if ($potion==12){
				$new=get_module_pref($n12,"magicarena",$id1)-1;
				if($new==0){
					clear_module_pref($n12,"magicarena",$id1);
				}elseif($new>0){
					set_module_pref($n12,$new,"magicarena",$id1);
				}
				$pot=get_module_pref($level12,"magicarena",$id1);
				$a=$pot*0.01;
				$b=$hp*$a;
				$hpa=$hp+$b;
				$c=$atk*$a;
				$atka=$atk+$c;
				$d=$pot*3;
				$defa=$def+$d;
				$opatka=$opatk;
				$opdefa=$opdef;
				$ophpa=$ophp;
				output("Taking a rosy flask out you take a hefty draught and toss the empty flask to the side, you feel your overall strength and stamina increase.");
				output_notl("`n`n");
			}
			if ($potion==13){
				$new=get_module_pref($n13,"magicarena",$id1)-1;
				if($new==0){
					clear_module_pref($n13,"magicarena",$id1);
				}elseif($new>0){
					set_module_pref($n13,$new,"magicarena",$id1);
				}
				$pot=get_module_pref($level13,"magicarena",$id1);
				$a=$pot*0.013;
				$b=$hp*$a;
				$hpa=$hp+$b;
				$c=$pot*0.014;
				$d=$atk*$c;
				$atka=$atk+$d;
				$e=$opdef*$c;
				$opdefa=$opdef-$e;
				$opatka=$opatk;
				$ophpa=$ophp;
				$defa=$def;
				output("Sipping on a shadowy mixture, you seem to blend into the shadows enshrouding this arena, you attack whilst your opponent seems unable to locate you.");
				output_notl("`n`n");
			}
			if ($potion==14){
				$new=get_module_pref($n14,"magicarena",$id1)-1;
				if($new==0){
					clear_module_pref($n14,"magicarena",$id1);
				}elseif($new>0){
					set_module_pref($n14,$new,"magicarena",$id1);
				}
				$pot=get_module_pref($level14,"magicarena",$id1);
				$a=$pot*0.016;
				$b=$opatk*$a;
				$atka=$atk+$b;
				$c=$opdef*$a;
				$defa=$def+$c;
				$d=$ophp*$a;
				$hpa=$hp+$d;
				$ophpa=$ophp-$d;
				$opdefa=$opdef-$c;
				$opatka=$opatk-$b;
				output("Taking a rainbow colored glass vial from your pack, you smash it to the ground between you both, a wall of flames appears, seeming to rob your opponent of some vital stats whilst increasing your own.");
				output_notl("`n`n");
			}
			//output("atk %s, def %s, hp %s, opatk %s, opdef %s, ophp %s`n`n",$atka, $defa, $hpa, $opatka, $opdefa, $ophpa);
			set_module_pref("lastpotion",$potion,"magicarena",$id1);
			$atkmin = round($atka*0.1);
			$atkmax = round($atk*0.5);
			$atknew = e_rand($atkmin,$atkmax);
			$defmin = round($def*0.12);
			$defmax = round($def*0.47);
			$defnew = e_rand($defmin,$defmax);
			$opamin = round($opatk*0.1);
			$opamax = round($opatk*0.47);
			$opanew = e_rand($opamin,$opamax);
			$opdmin = round($opdef*0.11);
			$opdmax = round($opdef*0.48);
			$opdnew = e_rand($opdmin,$opdmax);
			//if ($hp>$ophp){
				//$hpadjust = ($hp-$ophp)*0.015;
			//}
			//if ($hp<=$ophp){
			//	$hpadjust=0;
			//}
			//$hp2 = round($hp*0.005);
			$dam = round((($opanew-$atknew)*0.91)*(($opdnew-$defnew)*0.13));
			if ($dam>0){
				output("You hit your opponent for %s damage",$dam);
				addnav("Continue","runmodule.php?module=magicarena&op=magicfight&battle=$battleid");
				$hit=e_rand(1,15);
				switch($hit){
					case 1:
					case 5:
					$bonus = round($ophpa*0.07);
					output("Dodging under you opponents guard you execute a second quick attack for %s damage",$bonus);
					break;
					case 2:
					case 3:
					case 4:
					case 6:
					case 7:
					case 8:
					case 9:
					case 10:
					case 11:
					case 12:
					case 13:
					case 14:
					case 15:
					break;
				}
				set_module_pref("mfight",1,"arena",$id1);
				set_module_pref("mfight",2,"arena",$id2);
				$damnew = $dam+$bonus;
				$hpnew = $ophpa-$damnew;
				$sql="UPDATE " . db_prefix("arena") . " SET hp2 = '$hpnew' WHERE battleid = '$battleid'";
				db_query($sql);
				set_module_pref("mlasthit",$dam,"arena",$id1);
				set_module_pref("mbonushit",$bonus,"arena",$id1);
				$hpopp=$ophpa;
			}
			if ($dam==0){
				output("You miss");
				addnav("Continue","runmodule.php?module=magicarena&op=magicfight&battle=$battleid");
				set_module_pref("mfight",1,"arena",$id1);
				set_module_pref("mfight",2,"arena",$id2);
				set_module_pref("mlasthit",0,"arena",$id1);
				set_module_pref("mbonushit",0,"arena",$id1);
				$hpnew=$hpa;
				$hpopp=$ophpa;
				$sql="UPDaTE " . db_prefix("arena"). " SET hp1 = '$hpnew' WHERE battleid = '$battleid'";
				db_query($sql);
				$sql="UPDaTE " . db_prefix("arena"). " SET hp2 = '$hpopp' WHERE battleid = '$battleid'";
				db_query($sql);
			}
			if ($dam<0){
				output("You are riposted for %s damage",$dam);
				addnav("Continue","runmodule.php?module=magicarena&op=magicfight&battle=$battleid");
				set_module_pref("mfight",1,"arena",$id1);
				set_module_pref("mfight",2,"arena",$id2);
				$hit=e_rand(1,15);
				switch($hit){
					case 1:
					case 5:
					$bonus = round($ophpa*0.07);
					output("Recoiling from your opponent, you swiftly lift your weapon and catch them offguard for %s damage",$bonus);
					break;
					case 2:
					case 3:
					case 4:
					case 6:
					case 7:
					case 8:
					case 9:
					case 10:
					case 11:
					case 12:
					case 13:
					case 14:
					case 15:
					break;
				}
				$hpnew = $hpa+$dam;
				$hpopp = $ophpa-$bonus;
				$sql="UPDATE " . db_prefix("arena") . " SET hp1 = '$hpnew' WHERE battleid = '$battleid'";
				db_query($sql);
				$sql="UPDATE " . db_prefix("arena") . " SET hp2 = '$hpopp' WHERE battleid = '$battleid'";
				db_query($sql);
				set_module_pref("mlasthit",$dam,"arena",$id1);
				set_module_pref("mbonushit",$bonus,"arena",$id1);
			}
			$sql="UPDATE " . db_prefix("arena"). " SET atk1 = '$atka' WHERE battleid = '$battleid'";
			db_query($sql);
			$sql="UPDATE " . db_prefix("arena"). " SET atk2 = '$opatka' WHERE battleid = '$battleid'";
			db_query($sql);
			$sql="UPDATE " .db_prefix("arena"). " SET def1 = '$defa' WHERE battleid = '$battleid'";
			db_query($sql);
			$sql="UPDATE " .db_prefix("arena"). " SET def2 = '$opdefa' WHERE battleid = '$battleid'";
			db_query($sql);
			//output("atk %s, def %s, hp %s, opatk %s, opdef %s, ophp %s",$atka, $defa, $hpnew, $opatka, $opdefa, $hpopp);
		}
		if ($id == $id2){
			if ($potion==0){
				$opatka=$opatk;
				$opdefa=$opdef;
				$ophpa=$ophp;
				$hpa=$hp;
				$atka=$atk;
				$defa=$def;
			}
			if ($potion==1){
				$new=get_module_pref("Vires","magicarena",$id2)-1;
				if ($new==0){
					clear_module_pref("Vires","magicarena",$id2);
				}elseif ($new>0){
					set_module_pref("Vires",$new,"magicarena",$id2);
				}
				$pot = get_module_pref("level1","magicarena",$id2);
				$a=$pot*3;
				$opatka=$opatk+$a;
				$opdefa=$opdef;
				$ophpa=$ophp;
				$hpa=$hp;
				$atka=$atk;
				$defa=$def;
				output("You drink the Vires increasing your strength, enabling you to hit harder");
				output_notl("`n`n");
			}
			if ($potion==2){
				$new=get_module_pref($n2,"magicarena",$id2)-1;
				if ($new==0){
					clear_module_pref($n2,"magicarena",$id2);
				}elseif($new>0){
					set_module_pref($n2,$new,"magicarena",$id2);
				}
				$pot = get_module_pref("level2","magicarena",$id2);
				$a=$pot*5;
				$opatka=$opatk+$pot*5;
				$opdefa=$opdef;
				$ophpa=$ophp;
				$hpa=$hp;
				$atka=$atk;
				$defa=$def;
				output("You drink the Valde Vires increasing your strength, enabling you to hit harder %s",$opatka);
				output_notl("`n`n");
			}
			if ($potion==3){
				$new=get_module_pref($n3,"magicarena",$id2)-1;
				if ($new==0){
					clear_module_pref($n3,"magicarena",$id2);
				}elseif ($new>0){
					set_module_pref($n3,$new,"magicarena",$id2);
				}
				$pot=get_module_pref("level3","magicarena",$id2);
				$a=$pot*3;
				$opdefa=$opdef+$a;
				$b=$pot*0.01;
				$c=$ophp*$b;
				$ophpa=$ophp+$c;
				$opatka=$opatk;
				$hpa=$hp;
				$atka=$atk;
				$defa=$def;
				output("You take a Tutaminis potion from your pack and drink it, increasing your defence",$n3);
				output_notl("`n`n");
			}
			if ($potion==4){
				$new=get_module_pref($n4,"magicarena",$id2)-1;
				if($new==0){
					clear_module_pref($n4,"magicarena",$id2);
				}elseif($new>0){
					set_module_pref($n4,$new,"magicarena",$id2);
				}
				$pot=get_module_pref("level4","magicarena",$id2);
				$a=$pot*0.02;
				$b=$ophp*$a;
				$ophpa=$ophp+$b;
				$opatka=$opatk;
				$opdefa=$opdef;
				$hpa=$hp;
				$atka=$atk;
				$defa=$def;
				output("You take the glowing potion, Navitas from the pack, drinking it you feel your lifeforce increase.");
				output_notl("`n`n");
			}
			if ($potion==5){
				$new=get_module_pref($n5,"magicarena",$id2)-1;
				if($new==0){
					clear_module_pref($n5,"magicarena",$id2);
				}elseif($new>0){
					set_module_pref($n5,$new,"magicarena",$id2);
				}
				$pot=get_module_pref("level5","magicarena",$id2);
				$a=$pot*0.04;
				$b=$ophp*$a;
				$ophpa=$ophp+$b;
				$opatka=$opatk;
				$opdefa=$opdef;
				$hpa=$hp;
				$atka=$atk;
				$defa=$def;
				output("You take a gold flecked Confuto Navitas potion from your pack, swallowing it in one gulp, your lifeforce increases dramatically");
				output_notl("`n`n");
			}
			if ($potion==6){
				$new=get_module_pref($n6,"magicarena",$id2)-1;
				if($new==0){
					clear_module_pref($n6,"magicarena",$id2);
				}elseif($new>0){
					set_module_pref($n6,$new,"magicarena",$id2);
				}
				$pot=get_module_pref($level6,"magicarena",$id2);
				$a=$pot*0.05;
				$b=$opdef*$a;
				$opdefa=$opdef+$b;
				$c=$pot*0.01;
				$d=$hp*$c;
				$hpa=$hp-$d;
				$opatka=$opatk;
				$ophpa=$ophp;
				$atka=$atk;
				$defa=$def;
				output("Pulling a strangely colored mixture from your pack, you drink the Navi and suddenly take to the air, making you rather hard to hit");
				output_notl("`n`n");
			}
			if (get_module_pref("love","magicarena",$id1)<>0){
				$atka=0;
				$defa=0;
				$opatka=$opatk;
				$opdefa=$opdef;
				$ophpa=$ophp;
				$hpa=$hp;
			}
			if (get_module_pref("love","magicarena",$id2)<>0){
				$opatka=0;
				$opdefa=0;
				$opatka=$opatk;
				$opdefa=$opdef;
				$ophpa=$ophp;
				$hpa=$hp;
			}
			if ($potion==7){
				$new=get_module_pref($n7,"magicarena",$id2)-1;
				if($new==0){
					clear_module_pref($n7,"magicarena",$id2);
				}elseif($new>0){
					set_module_pref($n7,$new,"magicarena",$id2);
				}
				$pot=get_module_pref($level7,"magicarena",$id2);
				$love=get_module_pref("love","magicarena",$id2);
				$ll=$love-$pot;
				set_module_pref("love",$ll,"magicarena",$id2);
				output("You pull a pinkish liquid from the pack, before your opponent can react you throw the Diligo potion at them, they look at you in a love-crazed daze and appear to be defenceless");
				output_notl("`n`n");
				if ($ll<1){
					$opatka=$opatk+10;
					$opdefa=$opdef+10;
				}
				if ($ll>=1){
					$opatka=$opatk;
					$opdefa=$opdef;
				}
				$atka=$atk;
				$defa=$def;
				$ophpa=$ophp;
				$hpa=$hp;
			}
			if ($potion==8){
				$new=get_module_pref($n8,"magicarena",$id2)-1;
				if($new==0){
					clear_module_pref($n8,"magicarena",$id2);
				}elseif($new>0){
					set_module_pref($n8,$new,"magicarena",$id2);
				}
				$pot=get_module_pref($level8,"magicarena",$id2);
				$ad = get_module_pref("love","magicarena",$id2)-$pot;
				if ($ad<=0){
					set_module_pref("love",0,"magicarena",$id2);
					output("You smear a blackish paste over your skin, negating the effects of that love potion");
				}
				if ($ad>0){
					set_module_pref("love",$ad,"magicarena",$id2);
					output("You smear a blackish paste over your skin, somewhat negating the effects of that love potion");
				}
				output_notl("`n`n");
				$opatka=$opatk;
				$opdefa=$opdef;
				$ophpa=$ophp;
				$hpa=$hp;
			}
			if ($potion==9){
				$new=get_module_pref($n9,"magicarena",$id2)-1;
				if($new==0){
					clear_module_pref($n9,"magicarena",$id2);
				}elseif($new>0){
					set_module_pref($n9,$new,"magicarena",$id2);
				}
				$pot=get_module_pref($level9,"magicarena",$id2);
				$a=$pot*2;
				$atka=$atk-$a;
				$b=$pot*0.01;
				$c=$hp*$b;
				$hpa=$hp-$c;
				$d=$pot*3;
				$defa=$def-$d;
				$opatka=$opatk;
				$opdefa=$opdef;
				$ophpa=$ophp;
				output("Smashing a bottle filled with a misty cloud at their feet, you weaken your opponent");
				output_notl("`n`n");
			}
			if ($potion==10){
				$new=get_module_pref($n10,"magicarena",$id2)-1;
				if($new==0){
					clear_module_pref($n10,"magicarena",$id2);
				}elseif($new>0){
					set_module_pref($n10,$new,"magicarena",$id2);
				}
				$pot=get_module_pref($level10,"magicarena",$id2);
				$a=$pot*0.005;
				$b=$hp*$a;
				$hpa=$hp-$b;
				$opatka=$opatk;
				$opdefa=$opdef;
				$ophpa=$ophp;
				$atka=$atk;
				$defa=$def;
				output("Pulling out a vial containing a thick greenish mixture you throw it full force at your opponent, drawing some of their lifeforce away");
				output_notl("`n`n");
			}
			if ($potion==11){
				$new=get_module_pref($n11,"magicarena",$id2)-1;
				if($new==0){
					clear_module_pref($n11,"magicarena",$id2);
				}elseif($new>0){
					set_module_pref($n11,$new,"magicarena",$id2);
				}
				$pot=get_module_pref($level11,"magicarena",$id2);
				$a=$pot*0.013;
				$b=$hp*$a;
				$hpa=$hp-$b;
				$opatka=$opatk;
				$opdefa=$opdef;
				$ophpa=$ophp;
				$atka=$atk;
				$defa=$def;
				output("Your move suddenly, in one motion pulling a sparkling silver vial out, in the next grabbing your opponent and forcing them to drink the mixture, zapping some of their life");
				output_notl("`n`n");
			}
			if ($potion==12){
				$new=get_module_pref($n12,"magicarena",$id2)-1;
				if($new==0){
					clear_module_pref($n12,"magicarena",$id2);
				}elseif($new>0){
					set_module_pref($n12,$new,"magicarena",$id2);
				}
				$pot=get_module_pref($level12,"magicarena",$id2);
				$a=$pot*0.01;
				$b=$ophp*$a;
				$ophpa=$ophp+$b;
				$c=$opatk*$a;
				$opatka=$opatk+$c;
				$e=$pot*3;
				$opdefa=$opdef+$e;
				$hpa=$hp;
				$atka=$atk;
				$defa=$def;
				output("Taking a rosy flask out you take a hefty draught and toss the empty flask to the side, you feel your overall strength and stamina increase.");
				output_notl("`n`n");
			}
			if ($potion==13){
				$new=get_module_pref($n13,"magicarena",$id2)-1;
				if($new==0){
					clear_module_pref($n13,"magicarena",$id2);
				}elseif($new>0){
					set_module_pref($n13,$new,"magicarena",$id2);
				}
				$pota=get_module_pref($level13,"magicarena",$id2);
				$a=$pot*0.013;
				$b=$ophp*$a;
				$ophpa=$ophp+$b;
				$c=$pot*0.014;
				$d=$opatk*$c;
				$opatka=$opatk+$d;
				$e=$def*$c;
				$defa=$def-$e;
				$opdefa=$opdef;
				$hpa=$hp;
				$atka=$atk;
				output("Sipping on a shadowy mixture, you seem to blend into the shadows enshrouding this arena, you attack whilst your opponent seems unable to locate you.");
				output_notl("`n`n");
			}
			if ($potion==14){
				$new=get_module_pref($n14,"magicarena",$id2)-1;
				if($new==0){
					clear_module_pref($n14,"magicarena",$id2);
				}elseif($new>0){
					set_module_pref($n14,$new,"magicarena",$id2);
				}
				$pot=get_module_pref($level14,"magicarena",$id2);
				$a=$pot*0.016;
				$b=$atk*$a;
				$opatka=$opatk+$b;
				$c=$def*$a;
				$opdefa=$opdef+$c;
				$d=$hp*$a;
				$ophpa=$ophp+$d;
				$hpa=$hp-$d;
				$defa=$def-$c;
				$atka=$atk-$b;
				output("Taking a rainbow colored glass vial from your pack, you smash it to the ground between you both, a wall of flames appears, seeming to rob your opponent of some vital stats whilst increasing your own.");
				output_notl("`n`n");
				//output("opatka=%s",$opatka);
			}
			//output("atk %s, def %s, hp %s, opatk %s, opdef %s, ophp %s`n`n",$atka, $defa, $hpa, $opatka, $opdefa, $ophpa);
			set_module_pref("lastpotion",$potion,"magicarena",$id2);
			$atkmin = round($atk*0.1);
			$atkmax = round($atk*0.5);
			$atknew = e_rand($atkmin,$atkmax);
			$defmin = round($def*0.12);
			$defmax = round($def*0.47);
			$defnew = e_rand($defmin,$defmax);
			$opamin = round($opatk*0.1);
			$opamax = round($opatk*0.47);
			$opanew = e_rand($opamin,$opamax);
			$opdmin = round($opdef*0.11);
			$opdmax = round($opdef*0.48);
			$opdnew = e_rand($opdmin,$opdmax);
			//if ($ophp>$hp){
				//$hpadjust = ($ophp-$hp)*0.015;
			//}
			//if ($ophp<=$hp){
			//	$hpadjust=0;
			//}
			//$hp2 = round($ophp*0.005);
			$dam = round((($atknew-$opanew)*0.91)*(($defnew-$opdnew)*0.13));

			if ($dam > 0){
				output("You hit your opponent for %s damage",$dam);
				addnav("Continue","runmodule.php?module=magicarena&op=magicfight&battle=$battleid");
				$hit=e_rand(1,15);
				switch($hit){
					case 1:
					case 5:
					$bonus = round($hpa*0.07);
					output("Dodging under you opponents guard you execute a second quick attack for %s damage",$bonus);
					break;
					case 2:
					case 3:
					case 4:
					case 6:
					case 7:
					case 8:
					case 9:
					case 10:
					case 11:
					case 12:
					case 13:
					case 14:
					case 15:
					break;
				}
				set_module_pref("mfight",1,"arena",$id2);
				set_module_pref("mfight",2,"arena",$id1);
				$damnew = $dam+$bonus;
				$hpnew = $hpa-$damnew;
				$sql="UPDATE " . db_prefix("arena") . " SET hp1 = '$hpnew' WHERE battleid = '$battleid'";
				db_query($sql);
				set_module_pref("mlasthit",$dam,"arena",$id2);
				set_module_pref("mbonushit",$bonus,"arena",$id2);
				$hpopp=$ophpa;
			}
			if ($dam==0){
				output("You miss");
				addnav("Continue","runmodule.php?module=magicarena&op=magicfight&battle=$battleid");
				set_module_pref("mfight",1,"arena",$id2);
				set_module_pref("mfight",2,"arena",$id1);
				set_module_pref("mlasthit",0,"arena",$id2);
				set_module_pref("mbonushit",0,"arena",$id2);
				$hpnew=$hpa;
				$hpopp=$ophpa;
				$sql="UPDaTE " . db_prefix("arena"). " SET hp1 = '$hpnew' WHERE battleid = '$battleid'";
				db_query($sql);
				$sql="UPDaTE " . db_prefix("arena"). " SET hp2 = '$hpopp' WHERE battleid = '$battleid'";
				db_query($sql);
			}
			if ($dam<0){
				output("You are riposted for %s damage",$dam);
				addnav("Continue","runmodule.php?module=magicarena&op=magicfight&battle=$battleid");
				$hit=e_rand(1,15);
				switch($hit){
					case 1:
					case 5:
					$bonus = round($hpa*0.07);
					output("Recoiling from your opponent, you swiftly lift your weapon and catch them offguard for %s damage",$bonus);
					break;
					case 2:
					case 3:
					case 4:
					case 6:
					case 7:
					case 8:
					case 9:
					case 10:
					case 11:
					case 12:
					case 13:
					case 14:
					case 15:
					break;
				}
				set_module_pref("mfight",1,"arena",$id2);
				set_module_pref("mfight",2,"arena",$id1);

				$hpnew = $ophpa+$dam;
				$hpopp = $hpa-$bonus;
				$sql="UPDATE " . db_prefix("arena") . " SET hp2 = '$hpnew' WHERE battleid = '$battleid'";
				db_query($sql);
				$sql="UPDATE " . db_prefix("arena") . " SET hp1 = '$hpopp' WHERE battleid = '$battleid'";
				db_query($sql);
				set_module_pref("mlasthit",$dam,"arena",$id2);
				set_module_pref("mbonushit",$bonus,"arena",$id2);
			}
			$sql="UPDATE " . db_prefix("arena"). " SET atk1 = '$atka' WHERE battleid = '$battleid'";
			db_query($sql);
			$sql="UPDATE " . db_prefix("arena"). " SET atk2 = '$opatka' WHERE battleid = '$battleid'";
			db_query($sql);
			$sql="UPDATE " .db_prefix("arena"). " SET def1 = '$defa' WHERE battleid = '$battleid'";
			db_query($sql);
			$sql="UPDATE " .db_prefix("arena"). " SET def2 = '$opdefa' WHERE battleid = '$battleid'";
			db_query($sql);
			//output("`n`natk %s, def %s, hp %s, opatk %s, opdef %s, ophp %s",$atka, $defa, $hpnew, $opatka, $opdefa, $hpopp);
		}
	}
	//shop for selling ingredients in clan halls
	if ($op=="shopsell"){
		output("You enter the magical shop.  A gruff voice asks you `$ \"Ingredients or Potions, quick I don't have all day\"");
		addnav("Sell Ingredients","runmodule.php?module=magicarena&op=ingredientsell");
		addnav("Sell Potions","runmodule.php?module=magicarena&op=potionsell");
		addnav("Buy Potions","runmodule.php?module=magicarena&op=buypotions");
		addnav("Clan Halls","clan.php");
	}
	if ($op=="ingredientsell"){
		output("Please select how many you wish to sell of each ingredient");
		rawoutput("<form action='runmodule.php?module=magicarena&op=ingsellfinish' method='POST'>");
		$form = array(
           	"Ingredient Sell,note",
			"monkhood"=>"Monkhood,range,0,".$i0.",1",
           	"venom"=>"Basilisk Venom,range,0,".$i1.",1",
           	"hemlock"=>"Hemlock,range,0,".$i2.",1",
           	"mandrake"=>"Mandrake,range,0,".$i3.",1",
           	"scale"=>"Obsidian Dragon Scale,range,0,".$i4.",1",
    	);
    	require_once("lib/showform.php");
		showform($form, array(), true);
		$sell = translate_inline("Sell");
		rawoutput("<input type='submit' class='button' value='$sell'>");
		rawoutput("</form>");
		addnav("", "runmodule.php?module=magicarena&op=ingsellfinish");
		addnav("Clan Halls","clan.php");
	}
	if ($op=="ingsellfinish"){
		$monkhood=httppost('monkhood');
		$venom=httppost('venom');
		$hemlock=httppost('hemlock');
		$mandrake=httppost('mandrake');
		$scale=httppost('scale');
		output("You have sold:");
		output_notl("`n`n");
		if ($monkhood<>0){
			output("Monkhood: %s",$monkhood);
			$new=$i0-$monkhood;
			set_module_pref("monkhood",$new,"witchgarden",$id);
			output_notl("`n");
		}
		if ($venom<>0){
			output("Basilisk Venom: %s",$venom);
			output_notl("`n");
			$new=$i1-$venom;
			set_module_pref("venom",$new,"witchgarden",$id);
		}
		if ($hemlock<>0){
			output("Hemlock: %s",$hemlock);
			output_notl("`n");
			$new=$i2-$hemlock;
			set_module_pref("hemlock",$new,"witchgarden",$id);
		}
		if ($mandrake<>0){
			output("Mandrake: %s",$mandrake);
			output_notl("`n");
			$new=$i3-$mandrake;
			set_module_pref("mandrake",$new,"witchgarden",$id);
		}
		if ($scale<>0){
			output("Obsidian Dragon Scale: %s",$scale);
			output_notl("`n");
			$new=$i4-$scale;
			set_module_pref("scale",$new,"witchgarden",$id);
		}
		output_notl("`n");
		$price = ($monkhood+$venom+$hemlock+$mandrake+$scale)*get_module_setting("sellvalue");
		output("For a total of %s",$price);
		$session['user']['gold']+=$price;
		addnav("Return to shop","runmodule.php?module=magicarena&op=shopsell");
		addnav("Clan Halls","clan.php");
	}
	if ($op=="potionsell"){
		output("Please select how many of each potion you wish to sell");
		rawoutput("<form action='runmodule.php?module=magicarena&op=potsellfinish' method='POST'>");
		$form = array(
           	"Ingredient Sell,note",
			"pot1"=>"Vires,range,0,".$p1.",1",
           	"pot2"=>"Valde Vires,range,0,".$p2.",1",
           	"pot3"=>"Tutaminis,range,0,".$p3.",1",
           	"pot4"=>"Navitas,range,0,".$p4.",1",
           	"pot5"=>"Confuto Navitas,range,0,".$p5.",1",
           	"pot6"=>"Navi,range,0,".$p6.",1",
           	"pot7"=>"Diligo,range,0,".$p7.",1",
           	"por8"=>"Abominor,range,0,".$p8.",1",
           	"pot9"=>"Fragilitas,range,0,".$p9.",1",
           	"pot10"=>"Parum Nex,range,0,".$p10.",1",
           	"pot11"=>"Propinquus ut Nex,range,0,".$p11.",1",
           	"pot12"=>"Levo ut Sublimitas,range,0,".$p12.",1",
           	"pot13"=>"Umbra,range,0,".$p13.",1",
           	"pot14"=>"Flamma of Abyssus,range,0,".$p14.",1",
    	);
    	require_once("lib/showform.php");
		showform($form, array(), true);
		$sell = translate_inline("Sell");
		rawoutput("<input type='submit' class='button' value='$sell'>");
		rawoutput("</form>");
		addnav("", "runmodule.php?module=magicarena&op=potsellfinish");
		addnav("Clan Halls","clan.php");
	}
	if ($op=="potsellfinish"){
		$clanid=$session['user']['clanid'];
		$pot1=httppost('pot1');
		$pot2=httppost('pot2');
		$pot3=httppost('pot3');
		$pot4=httppost('pot4');
		$pot5=httppost('pot5');
		$pot6=httppost('pot6');
		$pot7=httppost('pot7');
		$pot8=httppost('pot8');
		$pot9=httppost('pot9');
		$pot10=httppost('pot10');
		$pot11=httppost('pot11');
		$pot12=httppost('pot12');
		$pot13=httppost('pot13');
		$pot14=httppost('pot14');
		output ("You have sold the following");
		output_notl("`n`n");
		if ($pot1<>0){
			$sql = "SELECT * FROM " .db_prefix("potions"). " WHERE name = '$n1' AND clanid = '$clanid'";
			$res = db_query($sql);
			$row = db_fetch_assoc($res);
			$amount=$row['amount']+$pot1;
			if(!db_num_rows($res)){
				$sql = "INSERT INTO " .db_prefix("potions") . " (potionsid,name,amount,clanid) VALUES ('0','$n1','1','$clanid')";
				db_query($sql);
			}elseif(db_num_rows($res)<>0){
				$potionsid=$row['potionsid'];
				$sql="UPDATE " . db_prefix("potions") . " SET amount = '$amount' WHERE potionsid = '$potionsid'";
				db_query($sql);
			}
			output("Vires: %s",$pot1);
			output_notl("`n`n");
			$new=$p1-$pot1;
			if ($new==0){
				clear_module_pref($n1);
			}
			if ($new<>0){
				set_module_pref($n1,$new,"magicarena",$id);
			}
			$price1=get_module_pref("level1")*$pot1;
		}
		if ($pot2<>0){
			$sql = "SELECT * FROM " .db_prefix("potions"). " WHERE name = '$n2' AND clanid = '$clanid'";
			$res = db_query($sql);
			$row = db_fetch_assoc($res);
			$amount=$row['amount']+$pot2;
			if(!db_num_rows($res)){
				$sql = "INSERT INTO " .db_prefix("potions") . " (potionsid,name,amount,clanid) VALUES ('0','$n2','1','$clanid')";
				db_query($sql);
			}elseif(db_num_rows($res)<>0){
				$potionsid=$row['potionsid'];
				$sql="UPDATE " . db_prefix("potions") . " SET amount = '$amount' WHERE potionsid = '$potionsid'";
				db_query($sql);
			}
			output("Valde Vires: %s",$pot2);
			output_notl("`n`n");
			$new=$p2-$pot2;
			if ($new==0){
				clear_module_pref($n2);
			}
			if ($new<>0){
				set_module_pref($n2,$new,"magicarena",$id);
			}
			$price2=get_module_pref("level2")*$pot2;
		}
		if ($pot3<>0){
			$sql = "SELECT * FROM " .db_prefix("potions"). " WHERE name = '$n3' AND clanid = '$clanid'";
			$res = db_query($sql);
			$row = db_fetch_assoc($res);
			$amount=$row['amount']+$pot3;
			if(!db_num_rows($res)){
				$sql = "INSERT INTO " .db_prefix("potions") . " (potionsid,name,amount,clanid) VALUES ('0','$n3','1','$clanid')";
				db_query($sql);
			}elseif(db_num_rows($res)<>0){
				$potionsid=$row['potionsid'];
				$sql="UPDATE " . db_prefix("potions") . " SET amount = '$amount' WHERE potionsid = '$potionsid'";
				db_query($sql);
			}
			output("Tutaminis: %s",$pot3);
			output_notl("`n`n");
			$new=$p3-$pot3;
			if ($new==0){
				clear_module_pref($n3);
			}
			if ($new<>0){
				set_module_pref($n3,$new,"magicarena",$id);
			}
			$price3=get_module_pref("level3")*$pot3;
		}
		if ($pot4<>0){
			$sql = "SELECT * FROM " .db_prefix("potions"). " WHERE name = '$n4' AND clanid = '$clanid'";
			$res = db_query($sql);
			$row = db_fetch_assoc($res);
			$amount=$row['amount']+$pot4;
			if(!db_num_rows($res)){
				$sql = "INSERT INTO " .db_prefix("potions") . " (potionsid,name,amount,clanid) VALUES ('0','$n4','1','$clanid')";
				db_query($sql);
			}elseif(db_num_rows($res)<>0){
				$potionsid=$row['potionsid'];
				$sql="UPDATE " . db_prefix("potions") . " SET amount = '$amount' WHERE potionsid = '$potionsid'";
				db_query($sql);
			}
			output("Navitas: %s",$pot4);
			output_notl("`n`n");
			$new=$p4-$pot4;
			if ($new==0){
				clear_module_pref($n4);
			}
			if ($new<>0){
				set_module_pref($n4,$new,"magicarena",$id);
			}
			$price4=get_module_pref("level4")*$pot4;
		}
		if ($pot5<>0){
			$sql = "SELECT * FROM " .db_prefix("potions"). " WHERE name = '$n5' AND clanid = '$clanid'";
			$res = db_query($sql);
			$row = db_fetch_assoc($res);
			$amount=$row['amount']+$pot5;
			if(!db_num_rows($res)){
				$sql = "INSERT INTO " .db_prefix("potions") . " (potionsid,name,amount,clanid) VALUES ('0','$n5','1','$clanid')";
				db_query($sql);
			}elseif(db_num_rows($res)<>0){
				$potionsid=$row['potionsid'];
				$sql="UPDATE " . db_prefix("potions") . " SET amount = '$amount' WHERE potionsid = '$potionsid'";
				db_query($sql);
			}
			output("Confuto Navitas: %s",$pot5);
			output_notl("`n`n");
			$new=$p5-$pot5;
			if ($new==0){
				clear_module_pref($n5);
			}
			if ($new<>0){
				set_module_pref($n5,$new,"magicarena",$id);
			}
			$price5=get_module_pref("level5")*$pot5;
		}
		if ($pot6<>0){
			$sql = "SELECT * FROM " .db_prefix("potions"). " WHERE name = '$n6' AND clanid = '$clanid'";
			$res = db_query($sql);
			$row = db_fetch_assoc($res);
			$amount=$row['amount']+$pot6;
			if(!db_num_rows($res)){
				$sql = "INSERT INTO " .db_prefix("potions") . " (potionsid,name,amount,clanid) VALUES ('0','$n6','1','$clanid')";
				db_query($sql);
			}elseif(db_num_rows($res)<>0){
				$potionsid=$row['potionsid'];
				$sql="UPDATE " . db_prefix("potions") . " SET amount = '$amount' WHERE potionsid = '$potionsid'";
				db_query($sql);
			}
			output("Navi: %s",$pot6);
			output_notl("`n`n");
			$new=$p6-$pot6;
			if ($new==0){
				clear_module_pref($n6);
			}
			if ($new<>0){
				set_module_pref($n6,$new,"magicarena",$id);
			}
			$price6=get_module_pref("level6")*$pot6;
		}
		if ($pot7<>0){
			$sql = "SELECT * FROM " .db_prefix("potions"). " WHERE name = '$n7' AND clanid = '$clanid'";
			$res = db_query($sql);
			$row = db_fetch_assoc($res);
			$amount=$row['amount']+$pot7;
			if(!db_num_rows($res)){
				$sql = "INSERT INTO " .db_prefix("potions") . " (potionsid,name,amount,clanid) VALUES ('0','$n7','1','$clanid')";
				db_query($sql);
			}elseif(db_num_rows($res)<>0){
				$potionsid=$row['potionsid'];
				$sql="UPDATE " . db_prefix("potions") . " SET amount = '$amount' WHERE potionsid = '$potionsid'";
				db_query($sql);
			}
			output("Diligo: %s",$pot7);
			output_notl("`n`n");
			$new=$p7-$pot7;
			if ($new==0){
				clear_module_pref($n7);
			}
			if ($new<>0){
				set_module_pref($n7,$new,"magicarena",$id);
			}
			$price7=get_module_pref("level7")*$pot7;
		}
		if ($pot8<>0){
			$sql = "SELECT * FROM " .db_prefix("potions"). " WHERE name = '$n8' AND clanid = '$clanid'";
			$res = db_query($sql);
			$row = db_fetch_assoc($res);
			$amount=$row['amount']+$pot8;
			if(!db_num_rows($res)){
				$sql = "INSERT INTO " .db_prefix("potions") . " (potionsid,name,amount,clanid) VALUES ('0','$n8','1','$clanid')";
				db_query($sql);
			}elseif(db_num_rows($res)<>0){
				$potionsid=$row['potionsid'];
				$sql="UPDATE " . db_prefix("potions") . " SET amount = '$amount' WHERE potionsid = '$potionsid'";
				db_query($sql);
			}
			output("Abominor: %s",$pot8);
			output_notl("`n`n");
			$new=$p8-$pot8;
			if ($new==0){
				clear_module_pref($n8);
			}
			if ($new<>0){
				set_module_pref($n8,$new,"magicarena",$id);
			}
			$price8=get_module_pref("level8")*$pot8;
		}
		if ($pot9<>0){
			$sql = "SELECT * FROM " .db_prefix("potions"). " WHERE name = '$n9' AND clanid = '$clanid'";
			$res = db_query($sql);
			$row = db_fetch_assoc($res);
			$amount=$row['amount']+$pot9;
			if(!db_num_rows($res)){
				$sql = "INSERT INTO " .db_prefix("potions") . " (potionsid,name,amount,clanid) VALUES ('0','$n9','1','$clanid')";
				db_query($sql);
			}elseif(db_num_rows($res)<>0){
				$potionsid=$row['potionsid'];
				$sql="UPDATE " . db_prefix("potions") . " SET amount = '$amount' WHERE potionsid = '$potionsid'";
				db_query($sql);
			}
			output("Fragilitas: %s",$pot9);
			output_notl("`n`n");
			$new=$p9-$pot9;
			if ($new==0){
				clear_module_pref($n9);
			}
			if ($new<>0){
				set_module_pref($n9,$new,"magicarena",$id);
			}
			$price9=get_module_pref("level9")*$pot9;
		}
		if ($pot10<>0){
			$sql = "SELECT * FROM " .db_prefix("potions"). " WHERE name = '$n10' AND clanid = '$clanid'";
			$res = db_query($sql);
			$row = db_fetch_assoc($res);
			$amount=$row['amount']+$pot10;
			if(!db_num_rows($res)){
				$sql = "INSERT INTO " .db_prefix("potions") . " (potionsid,name,amount,clanid) VALUES ('0','$n10','1','$clanid')";
				db_query($sql);
			}elseif(db_num_rows($res)<>0){
				$potionsid=$row['potionsid'];
				$sql="UPDATE " . db_prefix("potions") . " SET amount = '$amount' WHERE potionsid = '$potionsid'";
				db_query($sql);
			}
			output("Parum Nex: %s",$pot10);
			output_notl("`n`n");
			$new=$p10-$pot10;
			if ($new==0){
				clear_module_pref($n10);
			}
			if ($new<>0){
				set_module_pref($n10,$new,"magicarena",$id);
			}
			$price10=get_module_pref("level10")*$pot10;
		}
		if ($pot11<>0){
			$sql = "SELECT * FROM " .db_prefix("potions"). " WHERE name = '$n11' AND clanid = '$clanid'";
			$res = db_query($sql);
			$row = db_fetch_assoc($res);
			$amount=$row['amount']+$pot11;
			if(!db_num_rows($res)){
				$sql = "INSERT INTO " .db_prefix("potions") . " (potionsid,name,amount,clanid) VALUES ('0','$n11','1','$clanid')";
				db_query($sql);
			}elseif(db_num_rows($res)<>0){
				$potionsid=$row['potionsid'];
				$sql="UPDATE " . db_prefix("potions") . " SET amount = '$amount' WHERE potionsid = '$potionsid'";
				db_query($sql);
			}
			output("Propinquus ut Nex: %s",$pot11);
			output_notl("`n`n");
			$new=$p11-$pot11;
			if ($new==0){
				clear_module_pref($n11);
			}
			if ($new<>0){
				set_module_pref($n11,$new,"magicarena",$id);
			}
			$price11=get_module_pref("level11")*$pot11;
		}
		if ($pot12<>0){
			$sql = "SELECT * FROM " .db_prefix("potions"). " WHERE name = '$n12' AND clanid = '$clanid'";
			$res = db_query($sql);
			$row = db_fetch_assoc($res);
			$amount=$row['amount']+$pot12;
			if(!db_num_rows($res)){
				$sql = "INSERT INTO " .db_prefix("potions") . " (potionsid,name,amount,clanid) VALUES ('0','$n12','1','$clanid')";
				db_query($sql);
			}elseif(db_num_rows($res)<>0){
				$potionsid=$row['potionsid'];
				$sql="UPDATE " . db_prefix("potions") . " SET amount = '$amount' WHERE potionsid = '$potionsid'";
				db_query($sql);
			}
			output("Levo ut Sublimitas: %s",$pot12);
			output_notl("`n`n");
			$new=$p12-$pot12;
			if ($new==0){
				clear_module_pref($n12);
			}
			if ($new<>0){
				set_module_pref($n12,$new,"magicarena",$id);
			}
			$price12=get_module_pref("level12")*$pot12;
		}
		if ($pot13<>0){
			$sql = "SELECT * FROM " .db_prefix("potions"). " WHERE name = '$n13' AND clanid = '$clanid'";
			$res = db_query($sql);
			$row = db_fetch_assoc($res);
			$amount=$row['amount']+$pot13;
			if(!db_num_rows($res)){
				$sql = "INSERT INTO " .db_prefix("potions") . " (potionsid,name,amount,clanid) VALUES ('0','$n13','1','$clanid')";
				db_query($sql);
			}elseif(db_num_rows($res)<>0){
				$potionsid=$row['potionsid'];
				$sql="UPDATE " . db_prefix("potions") . " SET amount = '$amount' WHERE potionsid = '$potionsid'";
				db_query($sql);
			}
			output("Umbra: %s",$pot13);
			output_notl("`n`n");
			$new=$p13-$pot13;
			if ($new==0){
				clear_module_pref($n13);
			}
			if ($new<>0){
				set_module_pref($n13,$new,"magicarena",$id);
			}
			$price13=get_module_pref("level13")*$pot13;
		}
		if ($pot14<>0){
			$sql = "SELECT * FROM " .db_prefix("potions"). " WHERE name = '$n14' AND clanid = '$clanid'";
			$res = db_query($sql);
			$row = db_fetch_assoc($res);
			$amount=$row['amount']+$pot14;
			if(!db_num_rows($res)){
				$sql = "INSERT INTO " .db_prefix("potions") . " (potionsid,name,amount,clanid) VALUES ('0','$n14','1','$clanid')";
				db_query($sql);
			}elseif(db_num_rows($res)<>0){
				$potionsid=$row['potionsid'];
				$sql="UPDATE " . db_prefix("potions") . " SET amount = '$amount' WHERE potionsid = '$potionsid'";
				db_query($sql);
			}
			output("Flamma of Abyssus: %s",$pot14);
			output_notl("`n`n");
			$new=$p14-$pot14;
			if ($new==0){
				clear_module_pref($n14);
			}
			if ($new<>0){
				set_module_pref($n14,$new,"magicarena",$id);
			}
			$price14=get_module_pref("level14")*$pot14;
		}
		output_notl("`n");

		$price = ($price1+$price2+$price3+$price4+$price5+$price6+$price7+$price8+$price9+$price10+$price11+$price12+$price13+$price14)*get_module_setting("potionvalue");
		output("For a total of %s gems",$price);
		$newprice=$price*0.5;
		$session['user']['gems']+=$newprice;
		$goldvalue = $newprice*2000;
		addnav("Return to shop","runmodule.php?module=magicarena&op=shopsell");
		addnav("Clan Halls","clan.php");
	}
	//buy potions.
	if ($op=="buypotions"){
		$cost = get_module_setting("potionsale");
		output("You look at the potions arrayed around the shop, the owner informs you the price is %s gems",$cost);
		output_notl("`n`n");
		output("`b`@WARNING: If you have NEVER made this potion, your potion level is set at 0 for it, making it virtually useless until you make at least 1`b");
		output_notl("`n`n");
		$clan = $session['user']['clanid'];
		$buy1 = translate_inline("");
    	$pname = translate_inline("Potion Name");
        $wcost=translate_inline("Cost");
        $amt=translate_inline("Number Available");
	    $choose = translate_inline("Buy");
    	rawoutput("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>");
	    rawoutput("<tr class='trhead'>");
    	rawoutput("<td>$buy1</td><td>$pname</td><td>$amt</td>");
	    rawoutput("</tr>");
    	$sql = "SELECT * FROM " .db_prefix("potions"). " WHERE clanid = '$clan' AND amount <> 0 ORDER BY 'name'";
		$res = db_query($sql);
        for ($i=0;$i<db_num_rows($res);$i++){
	    	$row = db_fetch_assoc($res);
	    	$id = $row['potionsid'];
	        $name = $row['name'];
	    	$amount=$row['amount'];
        	rawoutput("<tr class='".($i%2?"trlight":"trdark")."'>");
            rawoutput("<td nowrap>[ <a href='runmodule.php?module=magicarena&op=buypotion&id=$id'>$choose</a>");
            addnav("","runmodule.php?module=magicarena&op=buypotion&id=$id");
	        output_notl("<td>`^%s</td>`0", $row['name'], true);
	        output_notl("<td>`&%s`0</td>", $row['amount'], true);
        	rawoutput("</tr>");
		}
        rawoutput("</table>");
        addnav("Clan Halls","clan.php");
	}
	if ($op=="buypotion"){
		$clanid = $session['user']['clanid'];
		$id = httpget("id");
		$sql = "SELECT * FROM " .db_prefix("potions"). " WHERE potionsid = '$id'";
		$res = db_query($sql);
		$row = db_fetch_assoc($res);
		$name = $row['name'];
		$amount=$row['amount'];
		$cost = get_module_setting("potionsale");
		$buyer = $session['user']['acctid'];
		if ($session['user']['gems']<$cost){
			output("Sorry you cannot afford this potion");
		}elseif ($amount==0){
			output("Sorry we appear to have sold out of this while you were browsing");
		}elseif ($session['user']['gems']>=$cost &&  $amount<>0){
			$new=get_module_pref($name)+1;
			set_module_pref($name,$new);
			output("The shopkeeper, carefully passes you %s `0",$name);
			$amount1=$amount-1;
    	    db_query("UPDATE " .db_prefix("potions"). " SET amount = '$amount1' WHERE potionsid = '$id'");

	        $session['user']['gems']-=$cost;
	        $amt = $cost*2000;
	    }
	    addnav("Clan Halls","clan.php?");
    }
	//hof for magic arena wins/losses
	if ($op=="hof"){
		page_header("Magical Arena HOF");
		$acc = db_prefix("accounts");
		$ar = db_prefix("arenastats");
		$sql = "SELECT $acc.name AS name,
		$acc.acctid AS acctid,
		$ar.magicwins AS wins,
		$ar.id FROM $ar INNER JOIN $acc
		ON $acc.acctid = $ar.id
		WHERE $ar.magicwins > 0 ORDER BY ($ar.magicwins+0)
		DESC limit ".get_module_setting("list")."";
		$result = db_query($sql);
		$rank = translate_inline("Magical Wins");
		$name = translate_inline("Name");
		output("`n`b`c`^Magical Arena Wins`n`n`c`b");
		rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center'>");
		rawoutput("<tr class='trhead'><td align=center>$name</td><td align=center>$rank</td></tr>");
		for ($i=0;$i < db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			if ($row['name']==$session['user']['name']){
				rawoutput("<tr class='trhilight'><td>");
			}else{
				rawoutput("<tr class='".($i%2?"trdark":"trlight")."'><td align=left>");
			}
			output_notl("%s",$row['name']);
			rawoutput("</td><td align=right>");
			output_notl("%s",$row['wins']);
			rawoutput("</td></tr>");
		}
		rawoutput("</table>");
		addnav("Back to HoF", "hof.php");
		villagenav();
		page_footer();
	}
	if ($op=="rules"){
		$magic = get_module_setting("name2","arena");
		output("`b`c`^RULES AND GUIDELINES FOR %s`0`b`c",$magic);
		output_notl("`n`n");
		output("`b`&1.  This arena takes into account your current stats upon challenging or accepting a challenge. A hit point cap is in place.");
		output_notl("`n`n");
		output("`^2.  You can only challenge someone one level below and two levels above you");
		output_notl("`n`n");
		output("`&3.  You cannot actually die here.");
		output_notl("`n`n");
		output("`^4.  There is a 3 minute time out, if your opponent or you have not made a move within 3 minutes, the player who did not move, will receive a defeat and the other a victory");
		output_notl("`n`n");
		output("`&5.  There are no buffs used in this arena, however you may choose to use potions.");
		output_notl("`n`n");
		output("`^6.  There is no dragon kill limit on whom you can attack, choose your opponents wisely.");
		output_notl("`n`n");
		output("`&7.  If you get stuck in here, and petition it, please include details, and your opponents name`b");
		addnav("Return to Arena","runmodule.php?module=arena&op=magic");
		villagenav();
	}
	page_footer();
?>