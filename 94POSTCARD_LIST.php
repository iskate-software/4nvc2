 <?php 
session_start();
require_once('../../tryconnection.php');

mysqli_select_db($tryconnection, $database_tryconnection);
$query_POSTCARDS = "SELECT * FROM POSTCARDS WHERE TYPE='$_GET[type]' ORDER BY SUBTYPE ASC";
$POSTCARDS = mysqli_query($tryconnection, $query_POSTCARDS) or die(mysqli_error($mysqli_link));
$row_POSTCARDS = mysqli_fetch_assoc($POSTCARDS);


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />

<title>POST CARDS</title>


<link rel="stylesheet" type="text/css" href="../../ASSETS/styles.css" />
<script type="text/javascript" src="../../ASSETS/scripts.js"></script>
<script type="text/javascript" src="../../ASSETS/navigation.js"></script>


<script type="text/javascript">

function bodyonload()
{
var leftpos = opener.window.screenX;
var toppos = opener.window.screenY;
moveTo(leftpos+50,toppos+80);
}

function highliteline(x,y){
document.getElementById(x).style.cursor="pointer";
document.getElementById(x).style.backgroundColor=y;
}

function whiteoutline(x){
document.getElementById(x).style.backgroundColor="#FFFFFF";
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
<table width="680" border="1" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC" frame="below" rules="rows">
  <tr>
    <td width="10" height="10" bgcolor="#000000" class="Verdana11Bwhite"></td>
    <td width="147" bgcolor="#000000" class="Verdana11Bwhite">Name</td>
    <td width="533" bgcolor="#000000" class="Verdana11Bwhite">Text</td>
  </tr>
  <tr valign="top" class="Verdana12">
    <td colspan="3">
    <div style="height:356px; overflow:auto;">
        <table width="660" border="1" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC" frame="below" rules="rows">
          <tr>
            <td width="10" height="0"></td>
            <td width="140"></td>
            <td width="535"></td>
          </tr>
         <?php do { ?>
              <tr valign="top" class="Verdana12" id="<?php echo $row_POSTCARDS['PCID']; ?>" onmouseover="CursorToPointer(this.id); highliteline(this.id, '#DCF6DD');" onmouseout="whiteoutline(this.id);" onclick="document.location='MODIFY_TEXT.php?type=<?php echo $_GET['type']; ?>&pcid=<?php echo $row_POSTCARDS['PCID']; ?>'">
                <td></td>
                <td><?php echo $row_POSTCARDS['SUBTYPE']; ?></td>
                <td><?php echo $row_POSTCARDS['MESSAGE']; ?></td>
              </tr>
         <?php } while ($row_POSTCARDS = mysqli_fetch_assoc($POSTCARDS)); ?>
		</table>
    </div>
    </td>
  </tr>
  <tr class="ButtonsTable">
    <td colspan="3" align="center">
   		<input name="add" type="button" class="button" id="add" value="ADD NEW" onclick=""/>
   		<input name="cancel" type="button" class="button" id="cancel" value="CANCEL" onclick="self.close();"/>    </td>
  </tr>
</table>

</form>
</body>
</html>
