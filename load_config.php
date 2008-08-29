<?
	$file = "/tmp/" . $id . ".cfg";
	if(file_exists($file))
		$lines = file($file);
	else
		$lines = array();
	$configs = array();

	foreach ($lines as $line_num => $line) {
		if($line_num == 0) {
			$autohide = (bool) rtrim($line);
			print "// $autohide";
		}
		elseif ($line_num == 1)
			$default_linger = (is_numeric(rtrim($line))) ? rtrim($line) : 20;
		elseif (rtrim($line) != "")
			array_push($configs, preg_split("/\s+/", rtrim($line), 3));
	}
?>

