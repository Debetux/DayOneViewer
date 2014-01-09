<?php

define('DIRECTORY', 'entries/'); 
setlocale(LC_ALL, 'fr');

/**
 * Require CFPropertyList
 */
require_once(__DIR__.'/libs/CFPropertyList/CFPropertyList.php');


$entries_by_date = array();
$entries = array();

if ($handle = opendir(DIRECTORY)) {
	while (false !== ($file = readdir($handle))) {
		if($file != '.' && $file != '..'){
			
			// All the files :
			$entries_array[] = $file;


			/*
			 * create a new CFPropertyList instance which loads the sample.plist on construct.
			 * since we know it's an XML file, we can skip format-determination
			 */
			$plist = new CFPropertyList\CFPropertyList(DIRECTORY.$file, CFPropertyList\CFPropertyList::FORMAT_XML);
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
	$i++;
endforeach;

?>

<?php 
	/* HERE IS THE VIEW */

	// Init some variables
	$last_month = null;
?>
<!doctype html>

<html lang="en">
<head>
	<meta charset="utf-8">

	<title>Day One</title>
	<meta name="description" content="The HTML5 Herald">
	<meta name="author" content="SitePoint">

	<?php $css = (!empty($_GET['style'])) ? $_GET['style'] : 'style'; ?>
	<link rel="stylesheet" href="./<?php echo $css; ?>.css?v=1.0">

	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>

<body>

	<ul id="contenu" class="journal">
		<?php foreach($entries as $key => $entry): ?>

			<?php // prep var
				$previous_month_is_different = ($last_month != date('n', $entry['Creation Date']));
				$next_month_is_different = (empty($entries[$key+1]) || date('n', $entry['Creation Date']) != date('n', $entries[$key+1]['Creation Date'])); // If next month is different from current month
			?>

			<!-- head -->
			<?php if($previous_month_is_different): // if previous month is different from current_month ?>
				<h3><?php echo date('F Y', $entry['Creation Date']); ?></h3>
				<hr>
				<ul class="entries">
					<br>
			<?php endif; ?>

			
			<!-- content -->
			<li class="entry_content">
				<h6 class="date"><a href="#<?php echo $entry['Creation Date']; ?>" id="<?php echo $entry['Creation Date']; ?>"><?php echo date('l j F Y, H:i', $entry['Creation Date']); ?></a></h6>
				<h3><strong><?php echo first_sentence($entry['Entry Text']); ?></strong></h3>
				<p><?php echo nl2br($entry['Entry Text']); ?></p>

				<br>

				<!-- location info and weather -->
				<?php if(!empty($entry['Location']['Place Name']) OR ! empty($entry['Location']['Locality']) OR !empty($entry['Location']['Administrative Area'])): ?>
					<div class="entry_info">
						<?php echo $entry['Location']['Place Name']; ?>, <?php echo $entry['Location']['Locality']; ?>, <?php echo $entry['Location']['Administrative Area']; ?> • 
						<?php echo $entry['Weather']['Celsius']; ?>°C <?php echo $entry['Weather']['Description']; ?>
					</div>
				<?php endif; ?>

				<?php if(! $next_month_is_different): ?> <hr class=""> <?php endif; ?>
			</li>

			<!-- end -->
			<?php if($next_month_is_different): ?>
				</ul>
				<br><br><br><br><br>
			<?php endif; ?>

			<?php $last_month = date('n', $entry['Creation Date']); ?>
		<?php endforeach; ?>
	</ul>

	<hr>
	<?php 
	/* foreach ($entries as $key => $value) {
		echo "<pre>";
		print_r($value);
		echo "</pre>";

	} */
	?>
</body>
</html>

<?php
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


?>