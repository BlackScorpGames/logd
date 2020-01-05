<?php
function lovers_chat_seth(){
	global $session;
	if (http::httpget("act")==""){
		output::doOutput("You make your way over to where %s`0 is sitting, ale in hand.", getsetting("bard", "`^Seth"));
		output::doOutput("Sitting down, and waiting for %s`0 to finish a song, you light your pipe.", getsetting("bard", "`^Seth"));
		addnav("Ask about your manliness","runmodule.php?module=lovers&op=chat&act=armor");
		addnav("Discuss Sports","runmodule.php?module=lovers&op=chat&act=sports");
	}elseif(http::httpget("act")=="sports"){
		output::doOutput("You and %s`0 spend some time talking about the recent dwarf tossing competition.", getsetting("bard", "`^Seth"));
		output::doOutput("Not wanting to linger around another man for too long, so no one \"wonders\", you decide you should find something else to do.");
	}else{
		$charm = $session['user']['charm']+e_rand(-1,1);
		output::doOutput("%s`0 looks you up and down very seriously.", getsetting("bard", "`^Seth"));
		output::doOutput("Only a friend can be truly honest, and that is why you asked him.");
		switch($charm){
			case -3: case -2: case -1: case 0:
				$msg = translator::translate_inline("You make me glad I'm not gay!");
				break;
			case 1: case 2: case 3:
				$msg = translator::translate_inline("I've seen some handsome men in my day, but I'm afraid you aren't one of them.");
				break;
			case 4: case 5: case 6:
				$msg = translator::translate_inline("I've seen worse my friend, but only trailing a horse.");
				break;
			case 7: case 8: case 9:
				$msg = translator::translate_inline("You're of fairly average appearance my friend.");
				break;
			case 10: case 11: case 12:
				$msg = translator::translate_inline("You certainly are something to look at, just don't get too big of a head about it, eh?");
				break;
			case 13: case 14: case 15:
				$msg = translator::translate_inline("You're quite a bit better than average!");
				break;
			case 16: case 17: case 18:
				$msg = translator::translate_inline("Few women would be able to resist you!");
				break;
			default:
				$msg = translator::translate_inline("I hate you, why, you are simply the most handsome man ever!");
		}
		output::doOutput("Finally he reaches a conclusion and states, \"%s`0\"", $msg);
	}
}
