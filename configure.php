<html>
<head>
<title>Flicker Configuration</title>
<style type="text/css">

td.button
{
	padding: 5
}

td, tr
{
	padding-top: 0;
	padding-bottom: 0;
	border: 0;
}

</style>

<script language=javascript>
<!--

<?
	include "load_config.php";
	echo "var next_id = " . count($configs) . ";";
	$min_pages = 3;
?>

var min_pages = <?= $min_pages ?>;


function add_page() {

	var table = document.getElementById("config_table");
	var row_index = table.rows.length;

	var row = table.insertRow(row_index);

	var cell0 = row.insertCell(0);
	var cell1 = row.insertCell(1);

	cell0.innerHTML = "URL: <input type=\"text\" size=\"80\" name=\"urls[]\"/>\
			Delay: <input type=\"text\" name=\"delays[]\" size=\"3\"/>\
			Name: <input type=\"text\" name=\"names[]\"/>";
	cell1.innerHTML = "<input type=\"button\" onclick=\"javascript:del_page(" + row_index + ")\" value=\"del\"/>";

	if (table.rows.length == min_pages + 1)
		for(var i = 0; i < table.rows.length; i++)
			table.rows[i].cells[1].innerHTML = "<input type=\"button\" onclick=\"javascript:del_page(" + i  + ")\" value=\"del\"/>";
		
}

function del_page(id) {
	var table = document.getElementById("config_table");
	var row_index = table.rows.length;

	for (var i = id + 1; i < row_index; i++) {
		table.rows[i].cells[1].innerHTML = "<input type=\"button\" onclick=\"javascript:del_page(" + (i - 1) + ")\" value=\"del\"/>";
	}

	table.deleteRow(id);

	if (table.rows.length <= min_pages)
		for(var i = 0; i < table.rows.length; i++)
			table.rows[i].cells[1].innerHTML = "<input type=\"button\" onclick=\"javascript:del_page(" + i  + ")\" disabled value=\"del\"/>";
}

// -->
</script>
</head>
<body>
	<form method="POST" action="<?= dirname($_SERVER['PHP_SELF']) . '/' ?>">
		Config: <input type="text" size="15" name="id" value="<?= $id ?>"/><br/>
		Autohide panel: <input type="checkbox" name="autohide" <? if ($autohide) print "checked"; ?>/>
		Default Delay: <input type="text" size="3" name="linger" value="<?= $default_linger ?>"/><br/>
		<table id="config_table">
<?

	if (count($configs) > 0) {
		foreach ($configs as $index => $config) {
			$url = $config[0];
			$linger = (is_null($config[1]) || $config[1] == "null") ? "" : $config[1];
			$name = (is_null($config[2]) || $config[2] == "null") ? "" : $config[2];

			echo "\t<tr><td>\n";
			echo "\tURL: <input type=\"text\" size=\"80\" name=\"urls[]\" value=\"" . $url . "\"/>\n";
			echo "\tDelay: <input type=\"text\" name=\"delays[]\" size=\"3\" value=\"" . $linger . "\">\n";
			echo "\tName: <input type=\"text\" name=\"names[]\" value=\"" . $name . "\">\n";

			echo "\t</td><td>\n";
			$disabled = (count($configs) <= $min_pages) ? "disabled" : "";
			echo "\t\t<input name=\"delbutton\" type=\"button\" onclick=\"javascript:del_page(" . $index . ")\" " .  $disabled . " value=\"del\"/>\n";
			echo "\t</td></tr>\n";
		}
	}
	else {

		for($i = 0; $i < $min_pages; $i++)
			echo "\t<tr><td>URL: <input type=\"text\" size=\"80\" name=\"urls[]\"/>
				\tDelay: <input type=\"text\" name=\"delays[]\" size=\"3\"/>
				\tName: <input type=\"text\" name=\"names[]\"/>
				\t</td><td>
				<input type=\"button\" onclick=\"javascript:del_page(" . $i . ")\" disabled value=\"del\"/>
				\t</td></tr>";
	}
	echo "</table>\n";

?>
		<span id="new_page">
			<input type="button" onclick="javascript:add_page()" value="new entry"/>
		</span>
		<input type="hidden" name="action" value="save"/>
		<input type="submit" value="save"/>
		<input type="button" value="cancel" onclick="javascript:history.back()"/>
	</form>
</body>
</html>

