<?php

include_once "config.php";
include_once "QueryResult.php";

class DBBrowser {

    private $db;

    function __construct($path_to_db) {
        try {
            $this->db = new SQLite3($path_to_db);
        } catch (ExtException $e) {
            print $e->errorMessage();
        }
    }

    public function getAllChild($parentId = 0) {
        $sql = "SELECT `OBJECT_ID`,`OB`.`ID`,`OB`.`DETAIL_ID`,`PARENT_ID`,`NAME`,`CLASS`,`DT`.`PATH` 
			FROM `OBJECTS` `OB`
	   LEFT JOIN `DETAILS` `DT`
			  ON `OB`.`DETAIL_ID`=`DT`.`ID`
		   WHERE `PARENT_ID` = '" . $parentId . "'";
        $res = $this->db->query($sql);
        $printed = false;
        $returnData = array();
        while ($result = $res->fetchArray()) {
            $resObj = new QueryResult($result['NAME']);
            if ($result['CLASS'] == 'container.storageFolder' || $result['CLASS'] == 'container.album.musicAlbum' || $result['CLASS'] == 'container.person.musicArtist' || $result['CLASS'] == 'container.genre.musicGenre' || $result['CLASS'] == 'container.playlistContainer' || $result['CLASS'] == 'container.album.photoAlbum') {
                $resObj->setPath("?id=".$result['OBJECT_ID']);
                $resObj->setType(0);
            } else {
                $resObj->setPath($result['PATH']);
                $pathInfo = pathinfo($result['PATH']);
                if (!$printed && strtolower($pathInfo['extension']) == 'mp3' || strtolower($pathInfo['extension']) == 'flac') {
                    $resObj->setType(1);
                    $printed = true;
                } else if (!$printed && strtolower($pathInfo['extension']) == 'jpg' || strtolower($pathInfo['extension']) == 'png') {
                    $resObj->setType(2);
                    $printed = true;
                }
                $resObj->setUrl($minidlnaserver . "/MediaItems/" . $result['DETAIL_ID'] . "." . $pathInfo['extension']);
            }
            $returnData[] = $resObj;
        }
        $res->finalize();
        return $returnData;
    }

}
