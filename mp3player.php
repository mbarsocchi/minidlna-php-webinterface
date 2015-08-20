<link rel="stylesheet" href="mp3player/player/css/styles.css" type="text/css" media="all" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script> 
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="mp3player/player/mp3playerplugin.js"></script> 
<?php
$DirectoryToScan = $folder;
$audioType = $exstension;
$totalCols = 0;
?>
<audio id="mp3Player-player">
	<source id="mp3Player-mp3" src="" />
	<p class="no-html5">Your browser doesn\'t support HTML5 audio</p>
</audio>

<div id="mp3Player-controls" class="mp3Player-group playerControls">
	<div id="mp3Player-buttons-container">
		<button id="mp3Player-prev" class="mp3controls disabled">Prev</button>
		<div id="mp3Player-play-pause">
			<button id="mp3Player-play" class="mp3controls">Play</button>
			<button id="mp3Player-pause" class="mp3controls display-off">Pause</button>
		</div>
		<button id="mp3Player-next" class="mp3controls disabled">Next</button>
	</div>

	<div id="mp3Player-progress-container" class="mp3Player-group progressContainer">
		<span id="mp3Player-currentTime"></span>
		<div id="mp3Player-progress" class="loaded"></div>
		<span id="mp3Player-remainingTime"></span>
	</div>

	<div id="mp3Player-volume-container" class="mp3Player-group">
		<span id="mp3Player-min-volume"></span>
		<div id="mp3Player-volume"></div>
		<span id="mp3Player-max-volume"></span>
	</div>
</div>

<table class="sortable" id="mp3Player-table">
	<colgroup>
	<?php $totalCols++; ?>
		<col class="title" />
	<?php if($artist == 'true'){ $totalCols++; ?>
		<col class="artist" />
	<?php } ?>
	<?php if($album == 'true'){ $totalCols++; ?>
		<col class="album" />
	<?php } ?>
	<?php if($length == 'true'){ $totalCols++; ?>
		<col class="play-time" />
	<?php } ?>
	<?php if($track == 'true'){ $totalCols++; ?>
		<col class="track" />
	<?php } ?>
	<?php if($genre == 'true'){ $totalCols++; ?>
		<col class="genre" />
	<?php } ?>
	<?php if($year == 'true'){ $totalCols++; ?>
		<col class="year" />
	<?php } ?>
	</colgroup>
	<thead>
		<tr class="heading">
				<th>Title</th>
			<?php if($artist == 'true'){ ?>
				<th>Artist</th>
			<?php } ?>
			<?php if($album == 'true'){ ?>
				<th>Album</th>
			<?php } ?>
			<?php if($length == 'true'){ ?>
				<th>Length</th>
			<?php } ?>
			<?php if($track == 'true'){ ?>
				<th>Track</th>
			<?php } ?>
			<?php if($genre == 'true'){ ?>
				<th>Genre</th>
			<?php } ?>
			<?php if($year == 'true'){ ?>
				<th>Year</th>
			<?php } ?>
		</tr>
	</thead>
	<tbody>
<?php
	$totalAudio = 0;
	foreach($files as $name => $path){
		$pathInfo= pathinfo($directory[$name]);
		if($pathInfo['extension']==$audioType){
			$totalAudio++;
			$FullFileName = $directory[$name];
			
			if (is_file($FullFileName)) {
				set_time_limit(30);
				if ($enableID3Tag){
					require_once('mp3player/player/php/getid3/getid3.php');
					$getID3 = new getID3;
					$ThisFileInfo = @$getID3->analyze($FullFileName);
					getid3_lib::CopyTagsToComments($ThisFileInfo);	
				}
				echo '<tr data-file="'.$path.'">';
				if($audioType == "mp3"){
					if(isset($ThisFileInfo['comments_html']['title'])){
						echo '<td class="title">'.$ThisFileInfo['comments_html']['title'][(count($ThisFileInfo['comments_html']['title'])-1)].'</td>';
					} else {
						echo '<td class="title">'.$name.'</td>';
					}
				} else {
					echo '<td class="title">'.$name.'</td>';
				}
				if($artist == 'true'){
					if(isset($ThisFileInfo['comments_html']['artist'])){
						echo '<td class="artist">'.$ThisFileInfo['comments_html']['artist'][(count($ThisFileInfo['comments_html']['artist'])-1)].'</td>';
					} else {
						echo '<td class="artist">Unknown Artist</td>';
					}
				}
				if($album == 'true'){	
					if(isset($ThisFileInfo['comments_html']['album'])){
						echo '<td class="album">'.$ThisFileInfo['comments_html']['album'][(count($ThisFileInfo['comments_html']['album'])-1)].'</td>';
					} else {
						echo '<td class="album">Unknown Album</td>';
					}
				}
				if($length == 'true'){
					echo '<td class="length">'.$ThisFileInfo['playtime_string'].'</td>';
				}
				if($track == 'true'){
					if(isset($ThisFileInfo['comments_html']['track'])){
						echo '<td class="track">'.$ThisFileInfo['comments_html']['track'][(count($ThisFileInfo['comments_html']['track'])-1)].'</td>';
					} else {
						echo '<td class="track"></td>';
					}
				}
				if($genre == 'true'){				
					if(isset($ThisFileInfo['comments_html']['genre'])){
						echo '<td class="genre">'.$ThisFileInfo['comments_html']['genre'][(count($ThisFileInfo['comments_html']['genre'])-1)].'</td>';
					} else {
						echo '<td class="genre"></td>';
					}
				}
				if($year == 'true'){				
					if(isset($ThisFileInfo['comments_html']['year'])){
						echo '<td class="year">'.$ThisFileInfo['comments_html']['year'][(count($ThisFileInfo['comments_html']['year'])-1)].'</td>';
					} else {
						echo '<td class="year"></td>';
					}
				}
				echo '</tr>';
			}
			
		}
	}
	
?>
	</tbody>
</table>