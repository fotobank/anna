<html>
    <body>
    <style>
body {
	background-color: #FFFFFF;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	margin: 8px;
}

td {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
}

input {
	background: #FFFFFF;
	border: 1px solid #cccccc;
}

td, input, select, textarea {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
}
fieldset {
	border: 1px solid #919B9C;
	font-family: Verdana, Arial;
	font-size: 10px;
	padding: 0px;
	margin: 0px;
	padding: 4px;
}

legend {
	color: #2B6FB6;
	font-weight: bold;
}
input, select, textarea {
	border: 1px solid #808080;
}
    </style>
    <h2>Image File Browser</h2>
<?php
    error_reporting(E_ALL);
    include("file_browser.class.php");    
    $abs_path = str_replace('\\','/',dirname(__FILE__)).'/';
    $o = new File_browser();
    $o->init('file_browser.class.php',
                $abs_path."images/04/",
                $abs_path."images/",
                0,4,10);
    $o->add_browser_jscripts('/ImageFileBrowser/js/');
    $o->place_browser();
?>

    </body>
</html>