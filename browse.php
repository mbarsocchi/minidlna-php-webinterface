<?php
if (`pidof mplayer`) {
	header('Location: controls.php');
	exit();
}
?>
<!DOCTYPE html>
<title>Media Centre PRO 3000 Extreme Edition</title>
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
<link rel="stylesheet" href="style.css">

<?php


function clientInSameSubnet() {
    if ((substr($_SERVER['REMOTE_ADDR'],0,8) == "192.168.") || ($_SERVER['REMOTE_ADDR'] == "127.0.0.1")) {
		$result =true;
	} else{
		$result =false;
	} 
	return $result;
}

$isLocal =clientInSameSubnet();

$_GET['b'] =  trim($_GET['b']) ;
$_GET['p'] = trim($_GET['p'], '/');

$bb = urlencode($_GET['b']);
$files = glob($_GET['b'] . $_GET['p'] . '/*');

$ff = array();
$dd = array();
foreach ($files as $f) {
	if ($f[0] == '.') continue;
	$bn = basename($f);
	
	$h = fopen($f,"r");
	$uf = fgets($h);
    fclose($h);
	
	if (is_file($f)) {
		$ff[$uf] = $bn;
	} else {
		$dd[$uf] = $bn;
	}
}

asort($ff);
asort($dd);


echo "<a href=\"index.php\">Main Menu</a>";

if ($_GET['p']) {
	$p = explode('/', $_GET['p']);
	array_pop($p);
	$s = urlencode(implode('/', $p));
	echo "<a href=\"browse.php?b={$bb}&p={$s}\">Up One Level</a>";
}

foreach ($dd as $uf => $bn) {
	echo "<a href=\"browse.php?b={$bb}&p={$uf}\">{$bn}</a>";
}

foreach ($ff as $uf => $bn) {
	if ($isLocal){
		echo "<a href=\"play.php?p={$uf}\">{$bn}</a>";
	}else {
		echo "<a href=\"$uf\">{$bn}</a>";
	}
}
