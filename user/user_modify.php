<?php
   session_start();
header("content-type:text/html; charset=gb2312");
?>
<?php
if (!isset($_SESSION['username'])){	
	echo "请先<a href='./loginfrm.php'>登录</a> ！";
	echo" <script>setTimeout('document.location.href=\"./loginfrm.php\"',0)</script>";  	
	exit;
}
error_reporting(0);
include('../../../config.inc');
function safe($str)
{ 
	$str=htmlspecialchars($str,ENT_QUOTES);
	return "'".mysql_real_escape_string($str)."'";
}
$action= trim($_POST["action"]);
if(empty($action))$action=trim($_GET['action']);
include('../include/dbconnect.php');
if (mysql_select_db(DBNAME))
{
	$userArray=empty($_POST["userArray"])?($_GET['userArray']):($_POST["userArray"]);
	$paras_array='';
	if(empty($userArray))
	{
	  echo " <script>window.alert(\"选择为空！\")</script>";
			echo " <script>setTimeout('document.location.href=\"javascript:history.back()\"',3)</script>";
			exit;	
	}			
	foreach($userArray as $value)
	{
		$value= safe($value);
		if(!empty($value))$paras_array[]= ' user_id='.$value;
	}
 
	$paras=implode(' or ',$paras_array);
	if($action == '删除')
	{
		if ($_SESSION['role']!='admin'){
			echo "You are not allowed to access this file!";
			echo "    <script>setTimeout('document.location.href=\"javascript:history.back()\"',3)</script>
		            ";
			exit;
		}
			
		$query="delete from svnauth_user where $paras";
		//echo $query;exit;
		$result=mysql_query($query);	
		$query="delete from svnauth_permission where $paras";
		$result=mysql_query($query);
		@include('./gen_passwd.php');
		echo " <script>setTimeout('top.location.href=\"../default.htm\"',0)</script>";
		//header("Cache-Control: no-cache");
		//echo "<script>window.history.back();</script>";
	}
	if($action == '设为超级用户')
	{
		if ($_SESSION['role']!='admin'){
			echo "You are not allowed to access this file!";
			echo "    <script>setTimeout('document.location.href=\"javascript:history.back()\"',3)</script>
		            ";
			exit;
		}
		$query="update svnauth_user set supervisor=1 where $paras";
		$result=mysql_query($query);
		echo " <script>setTimeout('top.location.href=\"../default.htm\"',0)</script>";
	}
	if($action == '取消超级用户')
	{
		if ($_SESSION['role']!='admin'){
			echo "You are not allowed to access this file!";
			echo "    <script>setTimeout('document.location.href=\"javascript:history.back()\"',3)</script>
		            ";
			exit;
		}
		$query="update svnauth_user set supervisor=0 where $paras";
		$result=mysql_query($query);
		echo " <script>setTimeout('top.location.href=\"../default.htm\"',0)</script>";
	}


	if($action=='chpasswd')
	{
		echo "暂不提供超级用户重置密码功能(好像没必要呢)。<br>如要修改密码，请使用<a href='../extension/pwdhelp.php'>修改密码工具</a>。<br>如您认为此功能是必要的，请到论坛<a href='http://www.scmbbs.com/cn/maia.php'>反馈</a>。";
		
	}
	if( $action == '编辑')
	{
		echo <<<HTML
		<form method="post" action="">
		<fieldset>
		<legend>编辑用户信息</legend>
		<input type=hidden name=action value='modify'>
		<table  cellspacing='1' cellpadding='0' width='70%' border='0' >
		<tr><th>用户名</th><th>姓名</th><th>工号</th><th>部门</th><th>邮件</th></tr>
HTML;
		if ($_SESSION['role']=='admin'){
			$query="select user_id,user_name,full_name,email,department,staff_no from svnauth_user where $paras";
			$result = mysql_query($query); 			
			while (($result)and($row= mysql_fetch_array($result, MYSQL_BOTH))) {
				$user_id=$row['user_id'];
				$user_name=$row['user_name'];
				$full_name=$row['full_name'];
				$staff_no=$row['staff_no'];
				$department=$row['department'];
				$email=$row['email'];
				echo "<tr><td><input type=hidden name='userArray[]' value='$user_id'>
				 <input type=text name='username[]' value='$user_name'></td>
				 <td><input type=text name='fullname[]' value='$full_name'></td>
				 <td><input type=text name='staff_no[]' value='$staff_no'></td>
				 <td><input type=text name='department[]' value='$department'></td>
				 <td><input type=text name='email[]' value='$email'></td></tr>";
			}
		}else{
			$query="select user_id,user_name,full_name,email,staff_no,department from svnauth_user where user_name='".$_SESSION['username']."'";
			$result=mysql_query($query);
			if($result)$row= mysql_fetch_array($result);
			$user_id=$row['user_id'];
			$user_name=$row['user_name'];				
				$full_name=$row['full_name'];
				$staff_no=$row['staff_no'];
				$email=$row['email'];
				$department=$row['department'];
			echo <<<HTML
			<tr><td><input type=hidden name='userArray[]' value="$user_id">	
				 <input type=text readonly name='username[]' value="$user_name" ></td>			 
				 <td><input type=text name='fullname[]' value="$full_name"></td>
				 <td><input type=text name='staff_no[]' value="$staff_no"></td>
				  <td><input type=text name='department[]' value="$department"></td>
				 <td><input type=text name='email[]' value="$email"></td></tr>
HTML;
		}	
		echo <<<HTML
		</table>
		<table style="position:relative;left:300px;top:20px" >
		<tr><td><input style="width:80" type=submit value="确定" ></td><td><input style="width:80" type=reset value="取消" onclick="turnback()"></td></tr>
	</table>
		</fieldset></form>
<br><a href="../priv/viewpriv.php?u=$user_id">查看我的权限详情</a>
HTML;
	}
	if( $action == '重置密码')
	{
		echo <<<HTML
		<form method="post" action="">
		<fieldset>
		<legend>重置用户密码</legend>
		<input type=hidden name=action value='chpasswd'>
		<table  cellspacing='1' cellpadding='0' width='70%' border='0' >
		<tr><th>用户名</th><th>新密码</th><th>新密码确认</th></tr>
HTML;
		if ($_SESSION['role']=='admin'){
			$query="select user_id,user_name,full_name from svnauth_user where $paras";
			$result = mysql_query($query); 			
			while (($result)and($row= mysql_fetch_array($result, MYSQL_BOTH))) {
				$user_id=$row['user_id'];
				$user_name=$row['user_name'];
				$full_name=$row['full_name'];
				echo "<tr><td><input type=hidden name='userArray[]' value='$user_id'>
				 <input type=text readOnly name='username[]' value='$user_name($full_name)'></td>
				 <td><input type=password name='passwd1[]' ></td>
				 <td><input type=password name='passwd2[]' ></td></tr>";
			}
		}	
		echo <<<HTML
		</table>
		<table style="position:relative;left:300px;top:20px" >
		<tr><td><input style="width:80" type=submit value="确定" ></td><td><input style="width:80" type=reset value="取消" onclick="turnback()"></td></tr>
	</table>
		</fieldset></form>
HTML;
	}


	if($action == 'modify')
	{
		$userid=$_POST['userArray'];
		$username=$_POST['username'];
		$fullname=$_POST['fullname'];
		$staff_no=$_POST['staff_no'];
		$email=$_POST['email'];	
		$department=$_POST['department'];
		if ($_SESSION['role']=='admin')
		{
			for($i=0;$i<count($userid);$i++)
			{
			  $username[$i]=safe($username[$i]);
			  $fullname[$i]=safe($fullname[$i]);
			  $userid[$i]=safe($userid[$i]);
			  $staff_no[$i]=safe($staff_no[$i]);
			  $email[$i]=safe($email[$i]);
			  $department[$i]=safe($department[$i]);
			  if(empty($userid[$i]))continue;
		  	  $query="update svnauth_user set user_name=$username[$i],full_name=$fullname[$i],staff_no=$staff_no[$i],email=$email[$i],department=$department[$i] where user_id=$userid[$i]";
		  	  mysql_query($query);
			}
		}else if($_SESSION['username']==$username[0])
		{
			$username[0]=safe($username[0]);
			  $fullname[0]=safe($fullname[0]);
			  $userid[0]=safe($userid[0]);
			  $staff_no[0]=safe($staff_no[0]);
			  $email[0]=safe($email[0]);
			  $department[0]=safe($department[0]);
			  if(empty($username[0]))continue;
			$query="update svnauth_user set full_name=$fullname[0],staff_no=$staff_no[0],email=$email[0],department=$department[0] where user_name=$username[0]";
			 mysql_query($query);
		}
		echo " <script>setTimeout('top.location.href=\"../default.htm\"',0)</script>";
		
	}
	
}
?>
<script language="javascript">
<!--
function turnback()
{ 
  // setTimeout('document.location.href="aa_fullview.php?y_site_domain='+site_domain+'&skey=6a817251398f92f265"',0)
  setTimeout('document.location.href="javascript:history.back()"',0)
}
-->
</script>
