<?php
require_once (__DIR__.'/../../lib/config.php');
require_once (__DIR__.'/../../lib/Debug/HackerConsole/Main.php');

new Debug_HackerConsole_Main(true);
call_user_func('F1');
F1(1);

function F1() {
	call_user_func('F2');
}

function F2() {
	echo "<pre>"; print_r(Debug_HackerConsole_Main::debug_backtrace_smart('call_user_func.*', true)); echo "</pre>";
	Debug_HackerConsole_Main::out("test");
}

?>
test
<hr>
<?show_source(__FILE__)?>