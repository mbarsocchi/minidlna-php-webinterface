 <?php
	$path_to_db = "/var/lib/minidlna/files.db";
	$minidlnaserver = "http://<your-local-web-interface:port";
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
	while($result = $res->fetchArray()) {
		//echo $result['CLASS'];die();
		if ($result['CLASS']== 'container.storageFolder' || $result['CLASS']== 'container.album.musicAlbum'
			|| $result['CLASS']=='container.person.musicArtist' || $result['CLASS']=='container.genre.musicGenre'
			|| $result['CLASS']=='container.playlistContainer' || $result['CLASS']=='container.album.photoAlbum'){
			print "<a href=\"?id=".$result['OBJECT_ID']."\">".$result['NAME']."</a></br>";
		}else {
			$pathInfo= pathinfo($result['PATH']);
			print "<a href=\"".$minidlnaserver."/MediaItems/".$result['DETAIL_ID'].".".$pathInfo['extension']."\">".$result['NAME']."</a></br>";
		}
	}
	$res->finalize();
?>
