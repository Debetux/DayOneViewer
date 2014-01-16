<?php

define('DIARY_DIRECTORY', 'Journal.dayone/'); 
define('TEMPLATES_DIRECTORY', __DIR__.'/templates/');
define('DEVICE_AGENT', 'nginx webserver');
define('HOST_NAME', 'Macbook');
define('OS_AGENT', 'Mac OS X/16.4');
define('SOFTWARE_AGENT', 'DayOneEdit 0.1');

setlocale(LC_ALL, 'fr');

/**
 * Require blablabla
 */
require_once(__DIR__.'/third_party/CFPropertyList/CFPropertyList.php');
require_once(__DIR__.'/classes/Diary.class.php');
require_once(__DIR__.'/classes/Entry.class.php');
require_once(__DIR__.'/classes/functions.php');


$requested_action = (empty($_GET['action'])) ? 'index' : $_GET['action'] ;

/**
 * Start the cache
 */
start_cache('cache/index', 0);


/* ******************************************************************************** */

// Parse the journal :
$diary = new Diary(DIARY_DIRECTORY);
$entries = $diary->getEntries();

// And here is the template !
include(TEMPLATES_DIRECTORY.'index.php');


/* ******************************************************************************** */


/**
 * End the cache
 */
end_cache('cache/index');

?>