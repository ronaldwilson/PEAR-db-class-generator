<?
ob_start();
if(isset($_POST['Submit']) && $_POST['Submit'] == "Submit")	{

	
	/************* MySql connection **********************/
	
	$user		=	"root";
	$password	=	"";
	$hostname	=	"localhost";
	$database	=	"{$_POST['dbName']}";
	
	$connection=mysql_connect($hostname,$user,$password);
	mysql_select_db($database,$connection);
	
	$target		=	"document/";
	
	/***************************************************/
		
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Document Creator</title>
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis" />
<style type="text/css">
<!--
.style3 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-style: normal;
	line-height: normal;
	font-weight: normal;
	font-variant: normal;
}
-->
</style>
</head>

<body>
<form name="form1" action="documentation.php" method="post">
<table width="100%"  border="0" cellspacing="3" cellpadding="0">
  <tr>
    <th colspan="3" scope="col"><h3 class="style3"> Document Creator </h3></th>
  </tr>
  <tr>
    <th width="37%" align="right" scope="col">Database Name </th>
    <th width="4%" scope="col">:</th>
    <th width="59%" align="left" scope="col"><input type="text" name="dbName" value="<?=@$_POST['dbName']?>" /></th>
  </tr>
  <tr>
    <th align="right" scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th align="left" scope="col"><input type="submit" name="Submit" value="Submit" /></th>
  </tr>
  <tr align="center" valign="top">
    <th colspan="3" scope="col" align="left">
	<?php
	if(isset($_POST['Submit']) && $_POST['Submit'] == "Submit")	{
		
		
			echo "<font color=RED>Database Name: $database</font><br>";
			echo "<font color=BLUE>Location    : $target</font><br>";
			$qry_db = mysql_query("show tables from $database");
			
			$filename	=	$database;
			$string 	=	"";
			$class		=	"";
			
			$ourFileName = $target.$filename.".doc";
			
			if(file_exists($ourFileName))
				unlink($ourFileName);		
		
			
  			while($table = mysql_fetch_row($qry_db)){
			
$class .= " Table: ".$table[0];
      		
			 $fields = @mysql_list_fields($database,$table[0]);
			
$class .= "

";
			
      		for($i=0;$i<@mysql_num_fields($fields);$i++) {
			
			$flg	=0;
			$ftype	="";
			$fflag	="";
			
			if(mysql_field_type($fields,$i) == "string"){
			  
			  $ftype	=	"varchar";
						
			} else if(mysql_field_type($fields,$i) == "blob"){
			  
			  $ftype	=	"text";
			
			  $flg =1;
			
			} else if(mysql_field_type($fields,$i) == "int"){
			  
			  $ftype	=	"integer";
			
			} else if(mysql_field_type($fields,$i) == "real"){
			  
			  $ftype	=	"float";
			
			} else {
			
			  $ftype	=	mysql_field_type($fields,$i);
			  
			}
			$fflag	= mysql_field_flags($fields,$i);
				
$class .= mysql_field_name($fields,$i)."   ".$ftype;

if($flg == 0){
$class .= "(".mysql_field_len($fields,$i).")"."   ".$fflag;
}

$class .= "
";
				
      		 }
$class .= "


";
			
	 		}
	}
	
	$ourFileHandle 	= fopen($ourFileName, 'a') or die("can't open file");
	
	if(fwrite($ourFileHandle, $class)){
	  echo "<font color=GREEN size='1' style='font-style:normal;font-weight:normal'>Successfully created    : <b>$filename.doc</b></font><br>";
	}else{
	  echo "<font color=BLUE size='1' style='font-style:normal;font-weight:normal'>Error       			  : Sorry can't create/write.</font><br>";
	}
	
	fclose($ourFileHandle);
	
?>
</th>
  </tr>
</table>
</form>
</body>
</html>
<?
mysql_close;
ob_flush();
?>