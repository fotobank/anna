<?php
// Include library code.
  require_once (__DIR__.'/../../lib/config.php');
  require_once (__DIR__.'/../../lib/Debug/HackerConsole/Main.php');
new Debug_HackerConsole_Main(true);

// Dump random structure.
debug($_SERVER);

// Mediator function for short call of out() method
function debug($msg)
{
	// Use call_user_func_array() to save caller context.
	call_user_func(array('Debug_HackerConsole_Main', 'out'), $msg);
}
?>
Press Ctrl+~ (tilde) to toggle the console.<br>
Move mouse pointer to debug message and make sure that
caller context is NOT inside debug() definition, but
points to debug() calling point.
<hr>
<?show_source(__FILE__)?>