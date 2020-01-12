<?php
output_notl("<form action='mail.php?op=write' method='post'>",true);
output::doOutput("`b`2Address:`b`n");
$to = translator::translate_inline("To: ");
$search = htmlentities(translator::translate_inline("Search"), ENT_COMPAT, settings::getsetting("charset", "ISO-8859-1"));
output_notl("`2$to <input name='to' id='to' value=\"".htmlentities(stripslashes(http::httpget('prepop')), ENT_COMPAT, settings::getsetting("charset", "ISO-8859-1"))."\">",true);
output_notl("<input type='submit' class='button' value=\"$search\">", true);
if ($session['user']['superuser'] & SU_IS_GAMEMASTER) {
	$from = translator::translate_inline("From: ");
	output_notl("`n`2$from <input name='from' id='from'>`n", true);
	output::doOutput("`7`iLeave empty to send from your account!`i");
}
rawoutput("</form>");
rawoutput("<script type='text/javascript'>document.getElementById(\"to\").focus();</script>");
