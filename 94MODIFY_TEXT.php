<?php 
session_start();
require_once('../../tryconnection.php');

mysqli_select_db($tryconnection, $database_tryconnection);
$query_POSTCARDS = "SELECT * FROM POSTCARDS WHERE PCID='$_GET[pcid]'";
$POSTCARDS = mysqli_query($tryconnection, $query_POSTCARDS) or die(mysqli_error($mysqli_link));
$row_POSTCARDS = mysqli_fetch_assoc($POSTCARDS);


if (isset($_POST['save']) && $_GET['pcid'] == '0'){
$insert_POSTCARDS = "INSERT INTO POSTCARDS (TYPE, SUBTYPE, MESSAGE, ADDRESS) VALUES ('$_GET[type]', '$_POST[xsubtype]', '".mysqli_real_escape_string($mysqli_link, $_POST['message'])."', '".mysqli_real_escape_string($mysqli_link, $_POST['address'])."')";
$insert_POSTCARDS = mysqli_query($tryconnection, $insert_POSTCARDS) or die(mysqli_error($mysqli_link));
}

else if (isset($_POST['save']) && $_GET['pcid'] != '0'){
$update_POSTCARDS = "UPDATE POSTCARDS SET SUBTYPE='$_POST[xsubtype]', MESSAGE='".mysqli_real_escape_string($mysqli_link, $_POST['message'])."', ADDRESS='".mysqli_real_escape_string($mysqli_link, $_POST['address'])."' WHERE PCID='$_GET[pcid]'";
$update_POSTCARDS = mysqli_query($tryconnection, $update_POSTCARDS) or die(mysqli_error($mysqli_link));
header("Location:POSTCARD_LIST.php?type=$_GET[type]");
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />

<title><?php if ($_GET['type'] == "Postcard") {echo 'POST CARD';} else {echo 'LETTER';}?></title>


<link rel="stylesheet" type="text/css" href="../../ASSETS/styles.css" />
<script type="text/javascript" src="../../ASSETS/scripts.js"></script>
<script type="text/javascript" src="../../ASSETS/navigation.js"></script>


<script type="text/javascript">

function bodyonload()
{
//var leftpos = opener.window.screenX;
//var toppos = opener.window.screenY;
//moveTo(leftpos+50,toppos+80);
document.modify_text.xsubtype.focus();
}


function highliteline(x,y){
}

function countchar(){
var xmessage = document.forms[0].message.value.replace(/\n/gi,'                                              ');
var chars=xmessage.length;
document.getElementById('maxnum').innerText = Math.floor(chars/46);

	if (chars><?php if ($_GET['type'] == "Postcard") {echo '690';} else {echo '1500';}?>){
	alert('I am sorry, but your message is too long. It\'s not my fault.');
	document.forms[0].message.value=document.forms[0].message.value.substr(0,689);	
	}


//var val=document.forms[0].message.value.replace(/\r/g,'').split('\n');
//vallength = val.length;
//	if(vallength > 15 ){
//	alert('I am sorry, but your message is too long. You can not enter\nmore than '+15+' lines. It\'s not my fault.');
//	document.forms[0].message.value=val.slice(0,-1).join('\n');
//	}
//document.getElementById('maxnum').innerText = vallength;
}

</script>

<style type="text/css">
.commentarea{
font-family:"Times New Roman", Times, serif;
font-size:16px;
}
</style>
</head>

<body onload="bodyonload()" onunload="bodyonunload()">

<form method="post" action="" name="modify_text" style="position:absolute; top:0px; left:0px; background-color:#FFFFFF;">

<table width="680" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="3" bgcolor="#000000" height="20" align="center" class="Verdana11Bwhite">Name: <input type="text" name="xsubtype" id="subtype" class="Input" value="<?php echo $row_POSTCARDS['SUBTYPE']; ?>" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)"/></td>
    </tr>
  <tr>
    <td height="35" colspan="3">&nbsp;</td>
    </tr>
  <tr>
    <td height="315" align="center" class="Verdana11Grey"><textarea name="message" cols="60" rows="15" class="commentarea" id="message" onkeyup="countchar()"><?php echo $row_POSTCARDS['MESSAGE']; ?></textarea><br /># of lines: <span id="maxnum"></span> <?php if ($_GET['type'] == 'Postcard') {echo '(max 15)';} else {echo '(max 30)';}?></td>
    <td width="40">&nbsp;</td>
    <td width="237"><textarea name="address" cols="35" rows="10" class="commentarea" id="address"s><?php echo $row_POSTCARDS['ADDRESS']; ?></textarea></td>
  </tr>
  <tr class="ButtonsTable">
    <td colspan="3" align="center">
    	<input name="save" type="submit" class="button" id="save" value="SAVE" />
      	<input name="codes" type="button" class="button" id="codes" value="CODES" onclick="window.open('../SETTINGS/CODES.php','_blank','width=415, height=417');" />
      	<input name="cancel" type="button" class="button" id="cancel" value="CANCEL" onclick="history.back()"/>
     </td>
   </tr>
</table>
<input type="hidden" name="subtype" value=""  />
</form>
</body>
</html>
