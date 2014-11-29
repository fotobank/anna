<?php
session_start();
require_once "class_protect_picture.php";
$ppicture = new protect_picture(session_id());
?>
<!doctype html public "-//W3C//DTD HTML 4.0 //EN">
<html>
<head>
       <title>Title here!</title>
</head>
<body>
<img SRC="/files/slides/slide-1.jpg" style="width: 60px" alt=" '">
<img src='/files/slides/slide-2.jpg' style="width: 60px">
<img src='/files/slides/slide-3.jpg' style="width: 60px">
<input type='image' src='/files/slides/slide-4.jpg'>
<img alt="Text" src="/files/slides/slide-5.jpg">
<img src=/files/slides/slide-5.jpg>
<img src="/files/slides/slide-5.jpg">


<div class="pic" style="overflow: hidden; width: 1024px; height: 521px; background: url(http://anna.od.ua/files/slides/slide-2.jpg) 0px 0px no-repeat;"><div class="mask" style="position: absolute; width: 100%; height: 100%; left: 0px; top: 0px; z-index: 1;"><div style="left: 0px; top: 0px; position: absolute; width: 1024px; height: 521px; opacity: 1; display: none; background-image: url(http://anna.od.ua/files/slides/slide-2.jpg); background-position: 0px 0px;"></div></div></div>


</body>
</html>
<?php
$ppicture->protect();
?>
