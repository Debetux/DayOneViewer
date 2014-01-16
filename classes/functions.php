<?php

function gen_title(&$text){

	$position_nl = stripos($text, "\n");

	if(!empty($position_nl) & $position_nl < 90)
		return substr($text, 0, $position_nl);
}

function format_content($text){

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