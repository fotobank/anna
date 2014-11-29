<?php
// This is class for maintaining the file system of a web server.
//
// It's features are:
//  list folders
//  list files: files can be listed separately according to types
//  file lists are displayed as links so they can be executed
//  the images listed can either be a list of filenames, or displayed as thumbnails
//  display a single image full size
//  edit a file's contents and save it 
//  display listing of a file
//  creates unique navigation links
//  upload a file or image to the server
//  download a file or image from the server

// License
//
// Copyright (C) 2006 George A. Clarke, webmaster@gaclarke.com, http://gaclarke.com/
//
// This program is free software; you can redistribute it and/or modify it under
// the terms of the GNU General Public License as published by the Free Software
// Foundation; either version 2 of the License, or (at your option) any later
// version.
//
// This program is distributed in the hope that it will be useful, but WITHOUT
// ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
// FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License along with
// this program; if not, write to the Free Software Foundation, Inc., 59 Temple
// Place - Suite 330, Boston, MA 02111-1307, USA.
//
// This class uses the php class of Slava Ivanov, Downloadfileclass, which can be found here:
//   http://www.phpclasses.org/browse/package/699.html
// The downloadfileclass.inc file is really all you need for this.
//
// Version 1.0.1   27Feb2006
// Corrected a number of errors in the code. Too many to list.
//
// Version 1.0.2   28Feb2006
// Corrected a couple of other errors that were missed.
//
// Version 1.0.3   03Oct2006
// Added a download feature for each file or image.
//

class abrowser
{
 var $startpath;
 var $adirs = array();
 var $afiles = array();
 var $backPath;
 var $subdir;
 var $roots;
 var $root;
 function abrowser(){
  $this->subdir = "";
  $this->startpath = "";
 }
// Reads the file system and gets the subfolders of the current folder
// The folder names are returned in an array, adirs[]
 function getDirs() {
  $dir = dir($this->startpath);
  while (false !== ($file = $dir->read())) {
   if($file != "." && $file != "..") {
    if (is_dir($this->startpath.$file)) {
     $this->adirs[] = $file;
    }
   }
  }
  $dir->close();
  return $this->adirs;
 }

// Display the list of files found in the current folder
//  as links to run them.
// If $links is true:
//   A link to list each file is displayed
//   A link to edit each file is displayed
// If a file is an image, the link is for displaying the image full size.
// The size and last updated date is also displayed for each file
 function showFiles($afiles,$links=TRUE){
  if(count($afiles)>0){
  foreach ($afiles as $dr){
   if($this->isimg($dr)){
    print "<a href='".$_SERVER['PHP_SELF']."?sp=$this->startpath&pic=$dr'>$dr</a>\n";
   }else{  
    $t=filemtime($this->startpath.$dr);
    print "<a href='$this->startpath$dr' target='_BLANK' TITLE='Last updated ".date('n/d/Y g:j A',$t)."'>$dr</a>\n";
   if($links){
    print "<a href='".$_SERVER['PHP_SELF']."?sp=$this->startpath&lst=$dr' title='List'>  List</a>\n";
    print "<a href='".$_SERVER['PHP_SELF']."?sp=$this->startpath&edit=$dr' title='Edit'>  Edit</a>\n";
   }
}
    $f=$this->startpath.$dr;
    print "<a href='dnload.php?f=$f' title='Download'>  Dwnld</a>\n";
   $size=filesize($this->startpath.$dr);
   $units = array(' B', ' KB', ' MB', ' GB', ' TB');
   for ($i = 0; $size > 1024; $i++) { $size /= 1024; }
   $size = round($size, 2).$units[$i];
   print "&nbsp;$size<br>";
   }
  }
  }

// This will display thumbnails of the image files in the array $this->afiles[]
// $perRow is the number of thumbnails in each row of displayed thumbnails
// Each thumbnail displyed is a link to display the image full size by itself
 function showThumbs($afiles,$perRow=1){
  print "<table border='0' width='100%'>\n";
  $i=0;
  if(count($afiles)>0){
  foreach($afiles as $pic){
   $i=$i%$perRow;
  if($i==0)print "<tr>\n";
   $pth=realpath($this->startpath.$pic);
   print "<td align='center' valign='top'>";
   print "<br>";
   print "<a href='".$_SERVER['PHP_SELF']."?sp=$this->startpath&pic=$pic'>\n";
   print "<img src='resize.php?f=$pth&w=100' border='0'>";
   print "</a><br>";
   print $pic."<br>";
   print "<a href='dnload.php?f=$pth' title='Download'>Dwnld</a>";
   print "</td>\n";
   if($i==$perRow-1)print "</tr><tr><td colspan='$perRow'><hr></td></tr>\n";
   $i=$i+1;
  }
  if($i<$perRow)print "</tr>";
 }
  print "</table>\n";
  return;
 }

// Shows a single image $pic full size
 function showPic($pic){
  $f=realpath($this->startpath.$pic);
  print "<form action='".$_SERVER['PHP_SELF']."?sp=$this->startpath' method='post' name='fedit'>\n";
  print "<input type='hidden' name='fil' value=$pic>\n";
  print "<FONT TITLE='Check OK and click [DELETE] to delete this document from the web server.\n";
  print " (Cannot be undone.)' FACE='Arial, Helvetica, sans-serif' SIZE=1>\n";
  print "<B>OK TO DELETE $pic? (Cannot be undone!)</B></FONT>\n";
  print "<input type='hidden' name='fdel' value='$f'>\n";
  print "<input type='checkbox' name='cdel'>\n";
  print "<input type='submit' name='delete' value='Delete'>\n";
  print "</form><br>";
  $pth=realpath("$this->startpath$pic");
  print "<br>";
  print "<center><img src='resize.php?f=$pth&w=500' border='0'><br>\n";
  print $pic;
  print "<br><a href='dnload.php?f=$f' title='Download'>Dwnld</a></center>\n";
  return;
 }

// Sets the root directory $rt for navigation purposes
// Returns the root directory
 function setRoot($rt){
  $this->root = $rt;
  return $this->root;
 }

// returns the root directory
 function getRoot(){
  return $this->root;
 }

// Sets the start path $path in $this->startpath
// Sets the subfolder $sb in$this->subdir
// Sets the relative path in $this->root for navigation purposes
// Adds the subfolder to the start path if it is included
// Startpath might be d:\wwwsites\mydomain\www\abrowser\images\
// subdir would be images/
// rel would be /abrowser/images/
 function setPath($path,$sb=""){
  $this->startpath = $path . $sb;
  $this->subdir = $sb;
  $this->rel = str_replace($this->root,"",$this->startpath);
  return TRUE;
 }

// Returns relative path $this->rel
 function getRel(){
  return $this->rel;
 }

// Returns the start path $this->startpath
 function getPath(){
  return $this->startpath;
 }

// Displays $this->startpath
 function showPath(){
  print "$this->startpath<br>\n";
  return;
 }

// Reads the file system and returns the filenames
//  in the current folder in the array $afiles
// $sb is the subfolder of the current path to look in
// $types is a list of file types to either include or exclude
//   (.php,.html,.asp) or $types="all" all file types are included or excluded
// if $x="x" and $types="all" then the listed file types will be excluded
//  and all others will be displayed
 function getFiles($sb,$types="all",$x=""){
  $dir = dir($this->startpath);
  $afiles = Array();
  while ($file = $dir->read()) {
   if (!is_dir($this->startpath.$file) && $file!=="..") {
    $fl=explode(".",$file);
    $ext=strtolower($fl[count($fl)-1]);
    unset($fl);
    if((strpos($types,$ext) !== FALSE && $x!=="x") || ($types == "all" && $file != "..")){
     $afiles[] = $file;
    }
    if((strpos($types,$ext) == FALSE && $x=="x")){
     $afiles[] = $file;
    }
   }
  }
  $dir->close();
//  $this->afiles=$afiles;
  return $afiles;
 }

// Display the directory names
// Each subfolder name listed is a link so that when clicked, it
//  will go to that subfolder and list the files in it
 function showDirs($links=TRUE){
  foreach ($this->adirs as $dr){
   if($links){
    print "<a href='".$_SERVER['PHP_SELF']."?sp=$this->startpath&sub=$dr'>$dr</href></a><br>\n";
   }else{
    print "$dr<br>\n";
   }
  }
 }

// Displays the navigation links 
 function showCrumbs(){
  $this->crumbs=explode("/",$this->rel);
  $n=count($this->crumbs)-1;
  unset($this->crumbs[$n]);
  print "<br>Navigation: ";
  print "<a href='".$_SERVER['PHP_SELF']."?sp=$this->root'>" .$this->roots . "</a>";
  if($n>0){
   for($i=0;$i<$n;$i++){
    print " <a href='".$_SERVER['PHP_SELF']."?sp=$this->root";
    for($j=0;$j<$i;$j++){
     print $this->crumbs[$j] . "/";
    }
    print "&sub=" . $this->crumbs[$i];
    print "'>" . $this->crumbs[$i] . "</a> / ";
   }
  }
  print "<br>";
  return;
 }

// When the List link is clicked on in the list of files,
//  it will list the contents of the file
 function listFile($fil){
  $f=realpath($this->startpath.$fil);
  $i=1;
  $handle = fopen($f, "rb");
  print "$f<br>";
  print "<a href='".$_SERVER['PHP_SELF']."?sp=$this->startpath'>Return</a><br><br>";
  while (!feof($handle)) {
   $line=fgets($handle,4096);
   $line=htmlentities($line);
   print str_pad($i, 4, "0", STR_PAD_LEFT).": ".$line."<br>";
   $i++;
  }
  fclose($handle);
 }

// Will display the contents of the file in a textbox so that it can be edited
// $fil is the filename to be edited
 function editFile($fil){
  $os=$_ENV['OS'];
  $sep=(strpos($os,"Windows")!==false)?"\\":"/";
  if(!$f=realpath($this->startpath.$fil)){
   $f=realpath($this->startpath).$sep.$fil;
  }
  $r= realpath(dirname($this->root)).$sep;
  $ff=str_replace($r,"",$f);
  print "$f<br>";
  $text=@file_get_contents($f);
  if($text===false)$text="";
  print "<form action='".$_SERVER['PHP_SELF']."?sp=$this->startpath' method='post' name='fedit'>\n";
  print "<input type='hidden' name='fil' value=$fil>\n";
  print "<input type='text' name=t1 value='' size='20'>\n<input type='button' value='Find'";
  print " name='srch' onClick=\"if(fedit.t1.value!=null && fedit.t1.value!='')findString(fedit.t1.value);return false\">\n";
  print "<FONT TITLE='Check OK and click [DELETE] to delete this document from the web server.\n";
  print " (Cannot be undone.)' FACE='Arial, Helvetica, sans-serif' SIZE=1>\n";
  print "<B>OK TO DELETE $fil? (Cannot be undone!)</B></FONT>\n";
  print "<input type='hidden' name='fdel' value='$f'>\n";
  print "<input type='checkbox' name='cdel'>\n";
  print "<input type='submit' name='delete' value='Delete'>\n";
  print "<br>$r";
  print "<input type='text' name='fpth' value='$ff' size='20'>\n";
  print "<input type='submit' name='save' value='Save'>\n";
  print "<input type='submit' name='cancel' value='Cancel'><br>\n";
  print "<br><textarea name='txt' rows='25' cols='100' WRAP='OFF' onclick='srange();return false;'>\n".htmlentities($text)."</textarea>\n";
  print "</form>\n";
  return;
 }

// Sets $this->roots with $rts, used for the navigation links
 function setRoots($rts){
  $this->roots=$rts;
  return;
 }

// Check to see if the current subfolder is empty
// Returns true if empty, false if not empty
 function isEmpty(){
 $os=$_ENV['OS'];
 $sep=(strpos($os,"Windows")!==false)?"\\":"/";
  $rp=realpath($this->startpath).$sep;
  $dir = dir($rp);
  while ($file = $dir->read()) {
   if($file !== "." && $file !== ".."){
    return FALSE;
   }
  }
  return TRUE;
 }

// is the file an image type?
function isimg($file){
    $imgs=".jpg,.png,.bmp,.gif";
    $fl=explode(".",$file);
    $ext=strtolower($fl[count($fl)-1]);
    unset($fl);
    return (strpos($imgs,$ext) === false)?false:true; 
}
}

// End of ABrowser Class
?>
