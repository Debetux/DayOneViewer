<?php $last_month = null; ?>
<!doctype html>

<html lang="en">
<head>
	<meta charset="utf-8">

	<title>Day One</title>
	<meta name="description" content="The HTML5 Herald">
	<meta name="author" content="SitePoint">

	<?php $css = (!empty($_GET['style'])) ? $_GET['style'] : 'DayOne'; ?>

	<link rel="stylesheet/less" type="text/css" href="css/<?php echo $css; ?>.less">

	<script type="text/javascript" src="./third_party/js/less.min.js"></script>
	<script>less.watch()</script>

	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>

<body>

	<ul id="contenu" class="journal">
		<?php foreach($entries as $key => $entry): ?>
			<?php if($key == 42) break; ?>
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
				<h3><strong><?php echo gen_title($entry['Entry Text']); ?></strong></h3>
				<?php if($entry['Media URL']): ?><div><img class="entry_media" src="<?php echo $entry['Media URL']; ?>" /></div><?php endif; ?>
				<p class="entry_text"><?php echo format_content($entry['Entry Text']); ?></p>
				
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


	<div class="underlined">WRITE :</div>

	<ul id="write_form" class="journal">
			<!-- content -->
			<li class="entry_content">
				<h6 class="date"><a href="#<?php echo $entry['Creation Date']; ?>" id="<?php echo $entry['Creation Date']; ?>"><?php echo date('l j F Y, H:i', time()); ?></a></h6>
				<br>
				<!-- <p><textarea id="editor" placeholder="Your entry text..."></textarea></p> -->
				<div id="editor"><textarea></textarea></div>
				
				<br>

				<!-- location info and weather -->
				
				<div class="entry_info hidden" id="entry_info_form">
					
				</div>

				<input type="hidden" name="latitude"/>
				<input type="hidden" name="longitude"/>

				<input type="submit" value="Add"/>

			</li>
	</ul>

	<script type="text/javascript">

	/*
	 * Location, useful when adding entries
	 */

	function getGPSCoordinate(position) {
		var infopos = "Position déterminée :\n";
		infopos += "Latitude : "+position.coords.latitude +"\n";
		infopos += "Longitude: "+position.coords.longitude+"\n";
		infopos += "Altitude : "+position.coords.altitude +"\n";

		var url = "https://maps.googleapis.com/maps/api/geocode/json?latlng="+position.coords.latitude+','+position.coords.longitude+'&sensor=false';
		var reversed_location;

		console.log(url);

		// Vanilla
		var httpRequest = new XMLHttpRequest()
		httpRequest.onreadystatechange = function (data) {
			if (httpRequest.readyState === 4) {
				if (httpRequest.status === 200) {
					reversed_location = JSON.parse(httpRequest.responseText);
					document.getElementById("entry_info_form").innerHTML = reversed_location['results'][0]['formatted_address'];
					document.getElementById("entry_info_form").style.display = "block";

					document.querySelector('[name=latitude]').value = position.coords.latitude;
					document.querySelector('[name=longitude]').value = position.coords.longitude;
				}
			}
		}
		httpRequest.open('GET', url)
		httpRequest.send()

	}

	if(navigator.geolocation)
		navigator.geolocation.getCurrentPosition(getGPSCoordinate);

	</script>

	<?php if(!empty($_GET['editor'])): ?>
	<style>
		#editor{
			width: 100%;
			height: 600px;
			padding: 3px;
			position: absolute;
		}


		#editor textarea{
			width: 100%;
			height: 600px;
			padding: 3px;
		}
	</style>

	<!-- Ace Editor -->
	<script src="third_party/Ace/ace.js" type="text/javascript" charset="utf-8"></script>
	<script src="third_party/Ace/theme-tomorrow_night_eighties.js" type="text/javascript" charset="utf-8"></script>
	<script src="third_party/Ace/mode-markdown.js" type="text/javascript" charset="utf-8"></script>

	<!-- JQuery -->
	<script type="text/javascript" src="third_party/js/jquery-2.0.3.min.js"></script>
	
	<script>

		function resizeAce() {
			return $('#editor').width($("#write_form").width());
		};
		//listen for changes
		$(window).resize(resizeAce);
		//set initially
		resizeAce();

		var editor = ace.edit("editor");
		editor.setTheme("ace/theme/tomorrow_night_eighties");
		var MarkdownMode = require("ace/mode/markdown").Mode;
		editor.getSession().setMode(new MarkdownMode());
		editor.renderer.setShowGutter(false);
		editor.setShowPrintMargin(false);

	</script>
	<?php endif; ?>


</body>
</html>