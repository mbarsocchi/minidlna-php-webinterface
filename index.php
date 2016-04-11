<?php
if (`pidof mplayer`) {
	header('Location: controls.php');
	exit();
}

	include("config.php");
	try {
		$db   = new SQLite3($path_to_db);
	} catch(ExtException $e) {
		print $e->errorMessage();  
	}
	if (isset($_GET['id'])){
		$parentId = $_GET['id'];
	}else {
		$parentId = 0;
	}

	$sql = "SELECT `OBJECT_ID`,`OB`.`ID`,`OB`.`DETAIL_ID`,`PARENT_ID`,`NAME`,`CLASS`,`DT`.`PATH` 
			FROM `OBJECTS` `OB`
	   LEFT JOIN `DETAILS` `DT`
			  ON `OB`.`DETAIL_ID`=`DT`.`ID`
		   WHERE `PARENT_ID` = '".$parentId."'";
	$res = $db->query($sql);
	$isRoot =true;
	if ($parentId != 0){
		$isRoot=false;
		$backId = substr($parentId,0,strrpos($parentId,'$'));
		$backId = $backId ==""?0:$backId ;
		print "<a href=\"?id=".$backId."\">..</a></br>";
	}

	$printed =false;
	$isMusic = false;
	$isImage = false;
	$files = array();
	$directory = array();
	while($result = $res->fetchArray()) {
		if ($result['CLASS']== 'container.storageFolder' || $result['CLASS']== 'container.album.musicAlbum'
			|| $result['CLASS']=='container.person.musicArtist' || $result['CLASS']=='container.genre.musicGenre'
			|| $result['CLASS']=='container.playlistContainer' || $result['CLASS']=='container.album.photoAlbum'){
			print "<a href=\"?id=".$result['OBJECT_ID']."#&gid=1&pid=1\">".$result['NAME']."</a></br>";
		}else {
			$pathInfo= pathinfo($result['PATH']);
			if (!$printed && strtolower($pathInfo['extension']) == 'mp3'|| strtolower($pathInfo['extension'])=='flac'){
				$folder = dirname($result['PATH']);
				$exstension = $pathInfo['extension'];
				$isMusic = true; 
				$printed =true;
			}else if (!$printed && strtolower($pathInfo['extension']) == 'jpg'|| strtolower($pathInfo['extension'])=='png'){
				$folder = dirname($result['PATH']);
				$exstension = $pathInfo['extension'];
				$isImage = true; 
				$printed =true;
			}
			$directory[$result['NAME']]=$result['PATH'];
			$files[$result['NAME']]=$minidlnaserver."/MediaItems/".$result['DETAIL_ID'].".".$pathInfo['extension'];			
		}
	}
?>
<!DOCTYPE html>
<title>Media Centre PRO 3000 Extreme Edition</title>
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
<link rel="stylesheet" href="style.css">
<link rel='stylesheet prefetch' href='photobrowser/css/photoswipe.css'>
<link rel='stylesheet prefetch' href='photobrowser/css/default-skin.css'>
<link rel="stylesheet" href="photobrowser/css/style.css">

<?php
	if($isRoot){
		echo "<a href=\"browse.php?b=".getcwd()."/stations\">Radio</a>";
	}
	if ($isMusic){
		include "mp3player.php"; 
	}elseif ($isImage){
		include "responsive.php"; 
	}else {
		foreach($files as $name => $path){
			echo "<a href=\"".$path."\">".$name."</a>
					";
		}
	}
	$res->finalize();

?>