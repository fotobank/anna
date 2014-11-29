<?php
// This is an application for maintaining the file system of a web server.
// It will display the file system from it's resident folder and down.
// It will not move up in the folder tree from it's resident folder.
//  (That is so a user can have his own folder and won't access others.)
//
// It's features are:
//  list folders (The folders are listed in the first column)
//  list files (The files are listed in the next three columns: images, scripts and .txt files)
//   (the images listed can either be a list of filenames, or displayed as thumbnails)
//   (click on the image name or thumbnail to show the full size image)
//  edit a file's contents and save it with the same name or different file name,
//   in same folder or another one
//  display listing of a file
//  delete a file
//  delete an empty folder
//  while editing a file, find a text string
//  navigate back up the tree using unique navigation links
//  create a new file and then enter text into it
//  create a new folder
//  upload a file into the currently displayed folder
//  download a file to your computer
//
// This application uses the php class of Olaf Lederer, Easy PHP Upload class, which can be found here:
//   http://www.phpclasses.org/browse/package/1841.html
// The upload_class.php file is really all you need for this application
//
// This application uses the php class of Slava Ivanov, Downloadfileclass, which can be found here:
//   http://www.phpclasses.org/browse/package/699.html
// The downloadfileclass.inc file is really all you need for this application



// Version 1.0.1   27Feb2006
// Corrected a number of errors in the code. Too many to list.
//
// Version 1.0.2   28Feb2006
// corrected a couple of other errors that were missed.
//
// Version 1.0.3   04Oct2006
// Added a download feature for each file or image to the class.
//   Version 1.0.2 should still work with the updated class.
//   Changes to this application were comments.

session_start();
?>

<script language="JavaScript">
<!--
// This JavaScript code performs the "Find" function in the "Edit" screen.
var TRange=null;
var TRange0=null;
var strFound=false;

function findString (str) {
 if (parseInt(navigator.appVersion)<4) return;
 if (navigator.appName=="Netscape") {

// NAVIGATOR-SPECIFIC CODE

  strFound=fedit.txt.find(str);
  if (!strFound) {
   strFound=fedit.txt.find(str,0,1);
   while (fedit.txt.find(str,0,1)) continue
  }
 }
 if (navigator.appName.indexOf("Microsoft")!=-1) {

// EXPLORER-SPECIFIC CODE

  if (TRange!=null) {
   TRange.collapse(false);
   strFound=TRange.findText(str);
   if (strFound) TRange.select();
  }
  if (TRange==null){
   TRange=document.fedit.txt.createTextRange();
   strFound=TRange.findText(str);
   if (strFound){
    TRange.select();
   }
  }
 }
 if (!strFound) alert ("String '"+str+"' not found!");
}

function srange(){
 if(TRange==null)TRange=document.fedit.txt.createTextRange();
 TRange.moveToPoint(window.event.x, window.event.y);
}

//-->
</script>

<?php
// The next two lines can be commented out after fully testing
//   the abrowser application in it's own folder on your server.
error_reporting(1);
error_reporting(2047);

require 'class.abrowser.php';
 $mq=(!get_magic_quotes_gpc())?false:true;
 $os=$_ENV['OS'];
 $sep=(strpos($os,"Windows")!==false)?"\\":"/";
 if(!isset($_SESSION['logged'])){
  $_SESSION["user"]="";
  $_SESSION["logged"]=FALSE;
 }

// Login Code. This is very simple for a single user/password.
// You should change this to whatever login system you are using.
if(isset($_POST['login'])){
 $user=$_POST['user'];
 $pw=md5($_POST['pw']);
// The initial username is "username" and password is "password"

// ########## Be sure to change it for your own ##############
  if("username"==$user && md5("password")==$pw){

  $_SESSION["user"]=$user;
  $_SESSION["logged"]=TRUE;
 }else{
  $_SESSION["user"]="";
  $_SESSION["logged"]=FALSE;
  print "Sorry, wrong login.";
 }
}
// Check for logged in user
if($_SESSION["logged"]!==true){
// If not logged in, display form for user/password
 print "<html><head></head><body>";
 print "<form method='post' action='".$_SERVER['PHP_SELF']."'><br>";
 print "Username: <input type='text' name='user' size=30><br>";
 print "Password: <input type='password' name='pw' size=30><br>";
 print "<input type='submit' name='login' value='Login'><br>";
 print "</form>";
 print "</body></html>";
// End of login code
}else{
// If logged in, proceed
 if(!isset($_SESSION["thumbs"]))$_SESSION["thumbs"]="no";
 if(isset($_GET['thmb']))$_SESSION["thumbs"]=$_GET['thmb'];
 (isset($_GET['sp'])) ? $spath=$_GET['sp'] : $spath="./";
 (isset($_GET['sub'])) ? $sub=$_GET['sub']."/" : $sub="";
 (isset($_GET['pic'])) ? $pic=$_GET['pic'] : $pic="";
 (isset($_GET['lst'])) ? $list=$_GET['lst'] : $list="";
 (isset($_GET['edit'])) ? $edit=$_GET['edit'] : $edit="";
 $dr = new abrowser();
 $rt=$dr->setRoot("./");
 $rt=$_SERVER['HTTP_HOST'] . str_replace("\\","/",dirname($_SERVER['PHP_SELF']));
 $dr->setPath($spath, $sub);
 $spath=$dr->getPath();
//  This is used for the navigation links at the top of each screen
 $roots = "http://" . $_SERVER['HTTP_HOST'] . str_replace("\\","/",dirname($_SERVER['PHP_SELF']));
 if(!(substr($roots,strlen($roots)-1,1) == "/")){
  $roots=$roots."/";
 }
 $dr->setRoots($roots);
 $rel = str_replace($rt,"",$dr->getPath());
 
// Save an edited file
 if(isset($_POST['save'])){
  
  $text=($mq)?stripslashes($_POST['txt']):$_POST['txt'];
  $fil=($mq)?stripslashes($_POST['fpth']):$_POST['fpth'];
  $r= realpath(dirname($dr->getRoot())).$sep;
  $fpth=$r.$fil;
  $fhandle=fopen($fpth,"wb");
  fwrite($fhandle,$text);
  fclose($fhandle);
  print $fpth." was saved successfully.<br>";
 }

// Delete a file
 if(isset($_POST['delete']) && $_POST['cdel']=='on'){
  $fil=$_POST['fil'];
  $fpth=($mq)?stripslashes($_POST['fdel']):$_POST['fdel'];
  if(unlink($fpth)){
  print "$fpth was deleted successfully.<br>";
  }
 }

// Delete an empty folder
 if(isset($_POST['ddelete']) && $_POST['cdel']=='on'){
  $rp=realpath($dr->startpath);
  $rp=$rp.$sep;
  $a=explode("/",$dr->startpath);
  unset($a[count($a)-1]);
  $b=$a[count($a)-1];
  $prnt=str_replace($a[count($a)-1]."/","",$dr->startpath);
  if(rmdir($rp)){
   $dr->setpath($prnt,"");
   print "Deleted folder $rp<br>\n";
  }
 }

// Upload a file to the server
// This uses the php class of Olaf Lederer, Easy PHP Upload class, which can be found here:
//   http://www.phpclasses.org/browse/package/1841.html
// The upload_class.php file is really all you need for this application
 if(isset($_POST['fupload'])){
  include("upload_class.php");
  $my_upload = new file_upload;
  $my_upload->upload_dir=realpath($dr->startpath).$sep;
// these are file types to allow to be uploaded
  $my_upload->extensions = array(".html",".htm",".php",".asp",".xml",".inc",".js",".xsl",".css",".txt",".gif",".jpg",".png",".ttf"); // specify the allowed extensions here
  $my_upload->max_length_filename = 50; // change this value to fit your field length in your database (standard 100) 
  $my_upload->the_temp_file = $_FILES['upload']['tmp_name'];
  $my_upload->the_file = $_FILES['upload']['name'];
  $my_upload->http_error = $_FILES['upload']['error'];
  $my_upload->replace = (isset($_POST['replace'])) ? $_POST['replace'] : "n"; // because only a checked checkboxes is true
  $my_upload->do_filename_check = "y"; // use this boolean to check for a valid filename
  $my_upload->upload();
  print "<br>".$my_upload->show_error_string()."<br>";
 }

// Create a new file or new folder
 if(isset($_POST['create'])){
// Create a new file (document)
  if($_POST['type']=="doc"){
  $edit=$sep.$_POST['new'];
   print "create $edit";
  }
// Create a new folder
  if($_POST['type']=="fold"){
   $sub=$_POST['new'];
   print "<br>create folder $sub<br>";
   $pth=realpath($spath).$sep.$sub;
   mkdir($pth,0777);
   $dr->setPath($spath,$sub."/");
   $rel = str_replace($rt,"",$dr->getPath());
  }
 }
// Get the directory list of folders
 $dirs=$dr->getDirs();
 print "<html><head></head><body>\n";
 print realpath($dr->getPath()).$sep;

// Show navigation links at the top
 $dr->showCrumbs();

 if($pic=="" && $list=="" && $edit==""){
// Display the forms above the folder and file lists
  print "<FORM METHOD='POST' ACTION='".$_SERVER['PHP_SELF']."?sp=" . $dr->getPath()."'>";

  if($dr->isEmpty()){
// if the current displayed folder is empty, show the delete folder button
   print "<FONT TITLE='Check OK and click [DELETE] to delete this folder from the web server.\n";
   print " (Cannot be undone.)' FACE='Arial, Helvetica, sans-serif' SIZE=1>\n";
   print "<B>OK TO DELETE THIS EMPTY FOLDER? (Cannot be undone!)</B></FONT>\n";
   print "<input type='checkbox' name='cdel'>\n";
   print "<input type='submit' name='ddelete' value='Delete'><br>\n";
  }

// Show the create new folder/file form
  print "Create New  <INPUT TYPE='RADIO' NAME='type' VALUE='doc' CHECKED>DOCUMENT -OR- ";
  print "<INPUT TYPE='RADIO' NAME='type' VALUE='fold'>FOLDER:   NAME ";
  print "<INPUT TYPE='TEXT' NAME='new' SIZE=14>";
  print "<INPUT TYPE='SUBMIT' name='create' VALUE='CREATE'>";
  print "</FORM>";

// Show the file upload form
  $max_size=2048*100;
  print "<form name='form1' enctype='multipart/form-data' method='POST' action='".$_SERVER['PHP_SELF']."?sp=" . $dr->getPath()."'>";
  print "<input type='hidden' name='MAX_FILE_SIZE' value='$max_size'>";
  print "Upload File <input type='file' name='upload' size='30'>";
  print "Replace ? <input type='checkbox' name='replace' value='y'>";
  print "<input type='submit' name='fupload' value='Upload'>";
  print "</form><br>";
 }
 print "<table border='1' width='100%'>\n";
 print "<tr valign='top'><td valign='top'>\n";
//  Show the directories in this column
 print "Directories<br>";
 $dr->showDirs(TRUE);
 print "<br>\n";

// Edit a file
 if($edit<>""){
  print "<td valign='top' bgcolor='#99ccff'>\n";
  $dr->editFile($edit);
  print "</td>";
 }

// Display a single image or photo
 if($pic<>""){
  print "<td valign='top' bgcolor='#99ccff'>\n";
  print "<a href='".$_SERVER['PHP_SELF']."?sp=$spath' align='left'>Go Back</a><br>\n";
  $dr->showPic($pic);
  print "</td>";
 }

// Display a listing of a file
 if($list<>""){
  print "<td valign='top' bgcolor='#99ccff'>\n";
  $dr->listFile($list);
  print "</td>";
 }

// Display the files in three columns
 if($pic=="" && $list=="" && $edit==""){
  print "</td><td bgcolor='#99ccff'>\n";

//  Show the images in this column
  $afiles=$dr->getFiles($sub,".jpg,.gif,.bmp,.png");
  print "Images&nbsp;&nbsp;&nbsp;";
// If "thumbs" is set, display the images as thumbnails
  if($_SESSION["thumbs"]=="no"){
   print "<a href='".$_SERVER['PHP_SELF']."?sp=$spath&thmb=yes' align='right'>Show Thumbs</a><br><br>\n";
   $dr->showFiles($afiles,false);
  }else{
// If not set, list the filenames
   print "<a href='".$_SERVER['PHP_SELF']."?sp=$spath&thmb=no'>Show List</a><br><br>\n";
   $dr->showThumbs($afiles,1);
  }
  print "</td><td bgcolor='#99ccff'>\n";

//  Show every file but these types in this column
  $afiles=$dr->getFiles($sub,".jpg,.gif,.bmp,.png,.txt","x");
  print "Scripts<br>";
  $dr->showFiles($afiles);
  print "</td><td bgcolor='#99ccff'>\n";

//  Show the .txt files in this column
  $afiles=$dr->getFiles($sub,".txt");
  print "Text Files<br>";
  $dr->showFiles($afiles);
 }

 print "</td></tr></table>\n";
 print "</body></html>";
}
// And that is all
?>


