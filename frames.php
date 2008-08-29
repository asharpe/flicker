<html>
<head>
<title>Flicker</title>
</head>
<frameset id="frames" <? if ($autohide) { print 'ROWS="*,3"'; } else { print 'ROWS="*,70"'; } ?> border="0" framespacing="0">
	<frame src="splash.php" name="pageFrame" frameborder="1">
	<frame src="panel.php?id=<?= $id ?>&state=<?= $state ?>" name="panelFrame" frameborder="0">
</frameset>
</html>

