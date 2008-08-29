<?

function do_ticker($id) {
	include "frames.php";
}

function do_configure($id) {
	include "configure.php";
}

function save_config($id) {

}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	if (! isset($_POST['id'])) # no id - request again
		include "request_id.php";

	else {
		$id = $_POST['id'];
		$file = "/tmp/" . $id . ".cfg";

		if (isset($_POST['action'])) {
			$action = $_POST['action'];
			
			if ($action == "configure")
				do_configure($id);
			elseif ($action == "save") {

				$num_pages = count($_POST['urls']);

				if ($num_pages >= 2) {

					$fd = fopen($file, "w");
					$written = 0;

					fwrite($fd, $_POST['autohide'] . "\n");
					fwrite($fd, $_POST['linger'] . "\n");
					
					for ($i = 0; $i < $num_pages; $i++) {

						if($_POST['urls'][$i] != "") {
							$delay = $_POST['delays'][$i];
							$name = $_POST['names'][$i];

							fwrite($fd, $_POST['urls'][$i]);
							fwrite($fd, " ");
							fwrite($fd, ($delay == "" || !is_numeric($delay)) ? "null" : $delay);
							fwrite($fd, " ");
							fwrite($fd, ($name == "") ? "null" : $name);
							fwrite($fd, "\n");
							$written++;
						}
					}

					fclose($fd);

					if($written < 2) {
						unlink($file);
						do_configure($id);
					}
					else
						do_ticker($id);
				}
				else
					do_configure($id);

			}
		}
		else {
			if (file_exists($file))
				do_ticker($id);

			else
				do_configure($id);
		}
	}
}
elseif ($_SERVER['REQUEST_METHOD'] == "GET") {

	if (! isset($_GET['id'])) { # ask them for an id
		$first_time = True;
		include "splash.php";
	}
	else {
		$id = $_GET['id'];

		if (isset($_GET['action'])) {
			if ($_GET['action'] == "configure")
				do_configure($id);
		}
		else
			do_ticker($id);
	}
}
?>

