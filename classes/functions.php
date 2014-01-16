<?php

function parse_journal($journal_path, &$entries){
	$entries_by_date = array();

	if ($handle = opendir($journal_path.'entries/')) {
		while (false !== ($file = readdir($handle))) {
			if($file != '.' && $file != '..'){
				
				// All the files :
				$entries_array[] = $file;


				/*
				 * create a new CFPropertyList instance which loads the sample.plist on construct.
				 * since we know it's an XML file, we can skip format-determination
				 */
				$plist = new CFPropertyList\CFPropertyList($journal_path.'entries/'.$file, CFPropertyList\CFPropertyList::FORMAT_XML);
				$o = $plist->toArray();
				$entries_by_date[$o["Creation Date"]] = $o;
				

			}
		}
	
		closedir($handle);
	}

	ksort($entries_by_date);

	// I want an index with number sorted by date, so we must do that again :
	$i = 0;
	foreach ($entries_by_date as $key_entry => $entry):
		$entries[$i] = $entries_by_date[$key_entry];

		// Media :
		$entries[$i]['Media URL'] = (file_exists($journal_path.'photos/'.$entries[$i]['UUID'].'.jpg')) ? $journal_path.'photos/'.$entries[$i]['UUID'].'.jpg' : false;
		$i++;
		if($i == 5) break; // for debug only
	endforeach;
}

/*** FUNCTIONS ***/
function first_sentence($text){
	return false;
		$max = 56;

		$position[] = stripos ($text, '.'); //find first dot position
		$position[] = stripos ($text, '!');
		$position[] = stripos ($text, '?');
		$position = min(array_filter($position));

		if($position) { //if there's a dot in our soruce text do
			$offset = $position + 1; //prepare offset
			$first_two = substr($text, 0, $position); //put two first sentences under $first_two

			return $first_two . '.'; //add a dot
		}

		else {  //if there are no dots
			//do nothing
			return false;
		}
}

function remove_first_sentence($text){

		$max = 56;

		$position[] = stripos ($text, '.'); //find first dot position
		$position[] = stripos ($text, '!');
		$position[] = stripos ($text, '?');
		$position = min(array_filter($position));
		$symbol = $text{$position + 1};

		if($position) { //if there's a dot in our soruce text do
			$offset = $position + 1; //prepare offset
			$first_two = substr($text, $position + 1); //put two first sentences under $first_two

			return $first_two . $symbol; //add a dot
		}

		else {  //if there are no dots
			//do nothing
			return false;
		}
}

function gen_title(&$text){

	$position_nl = stripos($text, "\n");

	if(!empty($position_nl) & $position_nl < 90)
		return substr($text, 0, $position_nl);
}

function format_content(&$text){

	$position_nl = stripos($text, "\n");

	if(!empty($position_nl) & $position_nl < 90)
		return nl2br(substr($text, $position_nl+1));

	else return nl2br($text);
}

function gen_uuid(){
	return substr(shell_exec('uuidgen | sed s/-//g'), 1);
}

function start_cache($cachefile, $cachetime = 3600){
        # Serve from the cache if it is younger than $cachetime
        if (file_exists($cachefile) && time() - $cachetime < filemtime($cachefile)) {
                echo "<!-- Cached copy, generated ".date('H:i', filemtime($cachefile))." -->\n";
                readfile($cachefile);
                exit;
        }
        ob_start(); #Start the output buffer
}

function end_cache($cachefile){
        # Cache the contents to a file
        $cached = fopen($cachefile, 'w');
        fwrite($cached, ob_get_contents());
        fclose($cached);
        ob_end_flush(); # Send the output to the browser
}

function pte($var){
	echo "<pre>";
	print_r($var);
	echo "</pre>";
}