<?php

//1.1 and 1.2 Changes in the URL-Link in the recieved mail
//1.3 Code Cleanup, added download-link

require_once("common.php");
require_once("lib/is_email.php");
require_once("lib/checkban.php");
require_once("lib/http.php");

function forgottenmail_getmoduleinfo(){
    $info = array(
        "name"=>"Forgotten Password Recovery by Mail Input",
        "version"=>"1.3",
        "author"=>"SexyCook",
        "category"=>"Administrative",
        "download"=>"http://www.carloscm.de/prog/lotgd/forgottenmail.zip",
        "allowanonymous"=>true,
        );
    return $info;
}
    
function forgottenmail_install(){
  	module_addhook("index");
    return true;
    }
    
function forgottenmail_uninstall(){
    return true;
    }

function forgottenmail_dohook($hookname,$args){
		global $session;

  	$op = httpget("op");

  	if($hookname == "index"){
  	        blocknav("create.php?op=forgot");
            addnav("Forgotten Password");
			      addnav("Enter Name","create.php?op=forgot&dummy=1");			      
			      addnav("Enter Mail","runmodule.php?module=forgottenmail&op=forgotmail");
  	}
	return $args;
}  	
  	
function forgottenmail_run(){
    global $session;
  	$op = httpget("op");
  	
    $repurl = str_replace( 'runmodule', 'create', $_SERVER['SCRIPT_NAME'] );  	

    page_header("Forgotten Password");
    if ($op=="forgotmail"){
 	    addnav("Login","index.php");			      
     	$charname = httppost('charname');
    	if ($charname!=""){
    		$sql = "SELECT acctid,login,emailaddress,emailvalidation,password FROM " . db_prefix("accounts") . " WHERE emailaddress='$charname'";
    		$result = db_query($sql);
    		if (db_num_rows($result)>0){
		    	$row = db_fetch_assoc($result);
    			if (trim($row['emailaddress'])!=""){
		    		if ($row['emailvalidation']==""){
				    	$row['emailvalidation']=substr("x".md5(date("Y-m-d H:i:s").$row['password']),0,32);
    					$sql = "UPDATE " . db_prefix("accounts") . " SET emailvalidation='{$row['emailvalidation']}' where emailaddress='{$row['emailaddress']}'";
		    			db_query($sql);
				    }
    				$subj = translate_mail("LoGD Account Verification",$row['acctid']);
		    		$msg = translate_mail(array("Someone from %s requested a forgotten password link for your account.  If this was you, then here is your"
				    		." link, you may click it to log into your account and change your password from your preferences page in the village square.\n\n"
						    ."If you didn't request this email, then don't sweat it, you're the one who is receiving this email, not them."
    						."\n\n  http://%s?op=val&id=%s\n\nThanks for playing!",
		    				$_SERVER['REMOTE_ADDR'],
				    		($_SERVER['SERVER_NAME'].($_SERVER['SERVER_PORT'] == 80?"":":".$_SERVER['SERVER_PORT']).$repurl),
						    $row['emailvalidation']
    						),$row['acctid']);
		    		mail($row['emailaddress'],$subj,$msg,"From: ".getsetting("gameadminemail","postmaster@localhost.com"));
				    output("`#Sent a new validation email to the address on file for that account.");
    				output("You may use the validation email to log in and change your password.");
		    	}else{
				    output("`#We're sorry, but that account does not have an email address associated with it, and so we cannot help you with your forgotten password.");
    				output("Use the Petition for Help link at the bottom of the page to request help with resolving your problem.");
		     	}
    		}else{
		    	output("`#Could not locate a character with that email.");
    			output("Look at the List Warriors page off the login page to make sure that the character hasn't expired and been deleted.");
    		}
    	}else{
    		rawoutput("<form action='runmodule.php?module=forgottenmail&op=forgotmail' method='POST'>");
    		output("`bForgotten Passwords:`b`n`n");
    		output("Enter your email: ");
    		rawoutput("<input name='charname'>");
    		output_notl("`n");
    		$send = translate_inline("Email me my password");
    		rawoutput("<input type='submit' class='button' value='$send'>");
    		rawoutput("</form>");
    	}
    }
    page_footer();
}    
?>
