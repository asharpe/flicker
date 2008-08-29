<html>
<head>
<title>Flicker Panel</title>
<style type="text/css">

a:link, a:visited
{
	color: blue;
}

.hidden {
	background-color: #00ffff;
}


html, body
{
	position: absolute;
	padding: 0;
	margin: 0;
	background-color: #eeeeee;
}

.conf
{
	float: left;
}

.nav
{
	float: left;
}

.loc
{
	float: left;
}

label
{
	padding-left: 0.4em;
}

.panel
{
	padding-top: 0;
}

.countdown
{
	float: right;
	text-align: center;
	font-weight: bold;
}

input.button
{
	width: 80;
}

#countdown
{
	color: red;
	font-size: xx-large;
}

</style>

<script language=javascript>
<!--
<?
	$id = $_GET['id'];
	include "load_config.php";
?>
var index = <?= count($configs) - 1 ?>;
var default_linger = <?= $default_linger ?>;
var cycle = true;
var linger = 0;
var URLs = new Array();
var prev;
var current;
var next;
var autohide = <?= ($autohide) ? "true" : "false" ?>;

function init() {
<?
	foreach ($configs as $index => $config) {
		$url = $config[0];
		$linger = (is_null($config[1]) || $config[1] == "null") ? "null" : $config[1];
		$name = (is_null($config[2]) || $config[2] == "null") ? "null" : "\"" . $config[2] . "\"";

		echo "\tURLs.push(new URL(\"" . $url . "\", " . $linger . ", " . $name . "));\n";
	}

?>

	// hide the panel if we need to
	hide_panel()
	setTimeout("action()", 1000);
}

function URL(url, delay, name) {
	this.url = url;
	this.name = name;

	if (delay == null)
		this.delay = default_linger;
	else
		this.delay = delay;

	this.getName = function() {
		if (name != null)
			return name;
		else
			return url;
	}
}

function action() {

	if (cycle) {
		if (linger == 0) {
			goto_next();
		}

		document.getElementById("countdown").innerHTML = linger;
		linger--;
	}

	setTimeout("action()", 1000);
}

function goto_url(loc) {
	parent.pageFrame.location.href = URLs[loc].url;
	document.getElementById("countdown").innerHTML = URLs[loc].delay;
}

function update_prev(loc) {
	document.getElementById("previous_loc").innerHTML = "<a href=\"" + URLs[loc].url + "\" title=\"" + URLs[loc].url + "\">" + URLs[loc].getName() + "</a>";
}
function update_current(loc) {
	document.getElementById("current_loc").innerHTML = "<a href=\"" + URLs[loc].url + "\" title=\"" + URLs[loc].url + "\">" + URLs[loc].getName() + "</a>";
}
function update_next(loc) {
	document.getElementById("next_loc").innerHTML = "<a href=\"" + URLs[loc].url + "\" title=\"" + URLs[loc].url + "\">" + URLs[loc].getName() + "</a>";
}

function pause_resume() {
	button = document.getElementById("toggle");
	label = document.getElementById("countdown_header");

	if (cycle) {
		button.value = "Resume";
		label.innerHTML = "Paused";
	}
	else {
		button.value = "Pause";
		label.innerHTML = "Remaining";
	}

	cycle = !cycle;
}

function increment_index(increment, value) {
	var num = (increment == null) ? 1 : increment;
	var base = (value == null) ? index : value;
	return (base + num) % URLs.length;
}

function decrement_index(value) {
	var num = (value == null) ? 1 : value;
	return (index - num + (num * URLs.length)) % URLs.length;
}

function goto_next() {
	var orig_cycle = cycle;
	cycle = false;

	goto_url(increment_index());

	update_prev(index);
	index = increment_index()

	update_current(index);
	update_next(increment_index());

	linger = URLs[index].delay;

	cycle = orig_cycle;
}

function goto_prev() {
	var orig_cycle = cycle;
	cycle = false;

	goto_url(decrement_index());

	update_next(index);
	index = decrement_index()

	update_current(index);
	update_prev(decrement_index());
	
	linger = URLs[index].delay;

	cycle = orig_cycle;
}

function configure() {
	// this allows the url to remain the same
	document.getElementById("config_form").submit()
//	parent.location.href="<?= dirname($_SERVER['PHP_SELF']) . '/main.php' ?>?id=<?= $_GET['id'] ?>&action=configure";
}

function show_panel() {
	if (autohide) {
		document.getElementById("panel").style.paddingTop = "0";
		document.getElementById("panel").className = "panel";
		parent.document.getElementById('frames').setAttribute('rows', '*,70', 0);
	}
}
function hide_panel() {
	if (autohide) {
		document.getElementById("panel").style.paddingTop = "3";
		document.getElementById("panel").className = "hidden";
		parent.document.getElementById('frames').setAttribute('rows', '*,3', 0);
	}
}
function autohidePanel() {
	autohide = document.getElementById("autohide").checked;
}

// -->
</script>
</head>
<body onload="init()" onmouseover="javascript:show_panel()" onmouseout="javascript:hide_panel()">
	<div id="panel" class="panel">
		<div id="conf" class="conf">
			<input class="button" value="Configure" type="button" onclick="javascript:configure()"/><br/>
			<label for="autohide" title="Autohide the panel?">
				Autohide
				<input id="autohide" type="checkbox" onclick="javascript:autohidePanel()" <?= ($autohide) ? "checked" : "" ?>/>
			</label>
		</div>
		<div class="nav">
			<input class="button" type="button" value="Previous" onclick="javascript:goto_prev()"/>
			<span id="previous_loc"></span><br/>

			<input id="toggle" class="button" type="button" value="Pause" onclick='javascript:pause_resume()'/>
			<span id="current_loc"></span><br/>

			<input class="button" type="button" value="Next" onclick="javascript:goto_next()"/>
			<span id="next_loc"></span>
		</div>
		<div class="countdown">
			<span id="countdown_header">Remaining</span><br>
			<span id="countdown"></span>
		</div>
	</div>		
	<form id="config_form" target="_top" method="POST" action="<?= dirname($_SERVER['PHP_SELF']) . '/' ?>">
		<input type="hidden" name="id" value="<?= $_GET['id'] ?>"/>
		<input type="hidden" name="action" value="configure"/>
	</form>
</body>
</html>

