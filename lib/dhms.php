<?php
// addnews ready
// translator ready
// mail ready
function dhms($secs,$dec=false){
	if ($dec===false) $secs=round($secs,0);
	return (int)($secs/86400).translator::translate_inline("d","datetime").(int)($secs/3600%24).translator::translate_inline("h","datetime").(int)($secs/60%60).translator::translate_inline("m","datetime").($secs%60).($dec?substr($secs-(int)$secs,1):"").translator::translate_inline("s","datetime");
	//use multiple translate_inlines as this function is not called too often...if you deactive stats...
}
?>
