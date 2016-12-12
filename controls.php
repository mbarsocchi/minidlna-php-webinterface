<?php

if (! `pidof mplayer`) {
	shell_exec('rm /tmp/mplayer-fifo');
	header('Location: index.php');
	exit();
}
if ($_GET['action']) {
	switch ($_GET['action']) {
		case 'pause':
			shell_exec('echo "pause" >/tmp/mplayer-fifo');
			$_GET['paused'] = 1;
			break;
			
		case 'play':
			shell_exec('echo "pause" >/tmp/mplayer-fifo');
			$_GET['paused'] = 0;
			break;
	
		case 'close':
			shell_exec('echo "quit" >/tmp/mplayer-fifo');
			while (`pidof mplayer`) {
				sleep(1);
			}
			break;
		case 'volup':
			shell_exec('echo "volume 100" >/tmp/mplayer-fifo');
			break;
			
		case 'prev':
			shell_exec('echo pt_step -1  >/tmp/mplayer-fifo');
			break;
			
		case 'next':
			shell_exec('echo pt_step 1 >/tmp/mplayer-fifo');
			break;
			
		case 'voldown':
			shell_exec('echo "volume -100" >/tmp/mplayer-fifo');
			break;
	}
	
	header('Location: controls.php?paused=' . $_GET['paused']);
	exit();
}
?>

<!DOCTYPE html>
<title>Media Centre PRO 3000 Extreme Edition</title>
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
<link rel="stylesheet" href="style.css">

<?php if ($_GET['paused']): ?>
	<a href="?action=play">Play</a>
<?php else: ?>
	<a href="?action=pause">Pause</a>
<?php endif; ?>
<a href="?action=volup">Vol ++</a>
<a href="?action=voldown">Vol --</a>
<a href="?action=next">Next >></a>
<a href="?action=prev"><< Prev</a>
<a href="?action=close">Quit</a>
