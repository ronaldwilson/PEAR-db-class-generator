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
	
	$target		=	"class/";
	
	/***************************************************/
		
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Class Creator</title>
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
<form name="form1" action="classCreator.php" method="post">
<table width="100%"  border="0" cellspacing="3" cellpadding="0">
  <tr>
    <th colspan="3" scope="col"><h3 class="style3"> Class Creator </h3></th>
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
			
			
  			while($table = mysql_fetch_row($qry_db)){
			
			$string 	=	"";
			$class		=	"";
			$filename	=	"";
			
			$filename	=	$table[0];
			
			   			   
$class .= "<?php
	class ".$table[0].' extends DB_table{ ' ;
      		
			 $fields = @mysql_list_fields($database,$table[0]);
			
$class .= "
			var $"."col = array(";
			
      		for($i=0;$i<@mysql_num_fields($fields);$i++) {
			
			$flg	=0;
			$ftype	="";
			
			if(mysql_field_type($fields,$i) == "string"){
			  
			  $ftype	=	"varchar";
						
			} else if(mysql_field_type($fields,$i) == "blob"){
			  
			  $ftype	=	"clob";
			
			  $flg =1;
			
			} else if(mysql_field_type($fields,$i) == "int"){
			  
			  $ftype	=	"integer";
			
			} else {
			
			  $ftype	=	mysql_field_type($fields,$i);
			  
			}
				
$class .= "
			'".mysql_field_name($fields,$i)."'	=>	array(
				  'type'	=> '".$ftype."',";

if($flg == 0){
$class .= "
				 'size' =>".mysql_field_len($fields,$i).",";
}

$class .= "
				  ),";
									
				$string	.=	mysql_field_name($fields,$i).",";
				$index	=	mysql_field_name($fields,0);
				
      		 }
$class .= "
);";
			
			$string = substr($string,0,(strlen($string)-1));
			
$class .= "
var $"."sql = array(		      
				  'list' 	=> array(
				  'select' 	=>	'$string',
				  'order'	 => '$index ASC',
				  ),";
				   
				$myFile = "generalFunctions.txt";
				$fh = fopen($myFile, 'r');
				$theData = fread($fh,filesize($myFile));
				fclose($fh);
				
				//$theData = ereg_replace("\n", "", $theData);
				
$class .= "
);
".$theData."
";
			
$class .= "}
";
			 
$class .= "
?>";
			 			 
				$ourFileName 	= $target.$filename.".php";
				$ourFileHandle 	= fopen($ourFileName, 'w') or die("can't open file");
				
				if(fwrite($ourFileHandle, $class)){
				  echo "<font color=GREEN size='1' style='font-style:normal;font-weight:normal'>Successfully created    : <b>$filename.php</b></font><br>";
				}else{
				  echo "<font color=BLUE size='1' style='font-style:normal;font-weight:normal'>Error       			  : Sorry can't create/write.</font><br>";
				}
				
				fclose($ourFileHandle);
	 		}
	}
	

?></th>
  </tr>
</table>
</form>
</body>
</html>
<?
mysql_close;
ob_flush();
?>