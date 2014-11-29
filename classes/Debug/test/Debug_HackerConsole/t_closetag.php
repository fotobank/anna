<?php
// Include library code.
require_once (__DIR__.'/../../lib/config.php');
require_once (__DIR__.'/../../lib/Debug/HackerConsole/Main.php');

// Create & attach hacker conole to HTML output.
new Debug_HackerConsole_Main(true);

// Dump string values.
for ($i=0; $i<10; $i++) {
	$sp = str_repeat(' ', $i);
	Debug_HackerConsole_Main::out(
		"$sp\ti=$i", "Counting", "#008800"
	);
}

// Output to default group.
Debug_HackerConsole_Main::out("Usual message");

// Dump random structure.
Debug_HackerConsole_Main::out($_SERVER, "Input");
?>
<?show_source(__FILE__)?>
<xmp>
This is the test page with any text.<br>
Press Ctrl+~ (tilde) to toggle the console.<br>
Move mouse to console message to see its generator 
context (file, line, function name).
<hr>

