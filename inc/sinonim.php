<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 21.07.14
 * Time: 19:49
 */
defined('_SECUR') or die('Доступ запрещен');
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$row = $_POST["text"];
	$znak= array(" ",".",",",":",";"," - ","!","?");
	$fileSin = __DIR__ . "/../classes/Synonym/Synonym.txt";
	$masSin=file($fileSin);

	$len2=strlen($row);
	for ($t=0; $t<count($masSin); $t++)
	{
		$sin=explode("|",$masSin[$t]);
		$len=strlen($sin[0]);
		if($len) {
			$pos=strpos($row, $sin[0]);
		if ($pos > 1)
		{
			if ( ($pos + $len) < $len2 )
				if ( (in_array($row[$pos + $len], $znak)) AND (in_array($row[$pos - 1], $znak)) )
				{
					$r=rand(2, count($sin));
					$OldStr=$row[$pos-1].$sin[0].$row[$pos+$len];
					$NewStr=$row[$pos-1].$sin[$r-1].$row[$pos+$len];
					$row=str_replace($OldStr, $NewStr, $row);
				}
		  }
	   }
	}
	$row=preg_replace("/[\r\n\s]+/"," ",$row);
	Error::var_dump('row');
}
?>
<BR>
<FORM ACTION="/index.php" METHOD="POST">
	<TABLE ALIGN="CENTER">
		<TR>
			<TD><STRONG>Введите текст для уникализации:</STRONG></TD></TR>
		<TR>
			<TD><textarea rows="10" cols="80" name="text"><?=isset($_POST["text"])?$_POST["text"]:'';?></textarea></TD></TR>
		<TR>
			<TD><HR></TD></TR>
		<TR>
			<TD><STRONG>Результат уникализации:</STRONG></TD></TR>
		<TR>
			<TD><textarea rows="10" cols="80"><?=isset($row)?$row:'';?></textarea></TD></TR>
		<TR>
			<TD><INPUT TYPE="submit" value="Уникализировать"></TD></TR>
	</TABLE>
</FORM>


