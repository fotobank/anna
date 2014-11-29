<html>
        <head>
            <title>Image File Browser</title>
            <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        </head>
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
	font-family: Verdana, Arial,sans-serif;
	font-size: 10px;
	padding: 0;
	margin: 0;
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
    <h2><a href="#" onclick="return false;">Image File Browser</a></h2>
<?php
    error_reporting(E_ALL);
    include("file_browser.class.php");    
    $abs_path = str_replace('\\','/',str_replace('C:','', dirname(__FILE__))).'/';

    $o = new File_browser();
    $o->init('file_browser.class.php',
                $abs_path."images/",
                $abs_path."images/",
                0,4,10,
                'file_browser_1',
                'img_view');

    $o->add_browser_jscripts('/ImageFileBrowser/js/');
    $o->place_browser();
?>

    </body>
</html>