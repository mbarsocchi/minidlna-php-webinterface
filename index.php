<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Language" content="it-IT" />
	<title>Minidlna browser</title>	    
    <link rel='stylesheet prefetch' href='photobrowser/css/photoswipe.css'>
	<link rel='stylesheet prefetch' href='photobrowser/css/default-skin.css'>
    <link rel="stylesheet" href="photobrowser/css/style.css">
</head>
<body>
<?php
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

	if ($parentId != 0){
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

	if ($isMusic){
		include "mp3player.php"; 
	}elseif ($isImage){
		include "responsive.php"; 
	}else {
		foreach($files as $name => $path){
			print "<a href=\"".$path."\">".$name."</a>
					<\br>
					";
		}
	}
	$res->finalize();
?>
</body>
</html>	