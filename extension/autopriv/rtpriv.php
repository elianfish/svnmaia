<?php
header("content-type:text/html; charset=gb2312");
error_reporting(0);
?>
<html>
<head>
  <title>svnȨ��������</title>
</head>
<style type='text/css'>
div{margin:15px}
fieldset{border:solid 1px gray;}
</style>
<body>
   <h2>svnȨ������</h2>
   <form action=./sendmail.php name=pwdform method=post onSubmit="return tCheck()">
   	<fieldset>
   <div id='inputblock'>
   		
   <table>
   <tr><td colspan=3>���svn�û�����<input type='text' name='username' size='14'  onBlur="loadTip();">* &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ���䣺<input type=text name='email' id='email'></td></tr>
   <tr><td colspan=3>����˵��:</td></tr>
   <tr><td colspan=3> <textarea id="comment" name="comment" cols="65" rows="5"></textarea></td></tr>
   <tr><td>�����url:<input type=text name='wurl' size='55'  onBlur="checkurl();"></td>
       <td><select name="wpriv"><option value='r' label='ֻ��'>ֻ��</option>
<option value='w' label='��д'>��д</option>
</select></td></tr>
  <tr><td colspan=3><label id='urltip' style='color:red;font-size:12px;'></label></td></tr>
   <tr align=center bgcolor='#B6C6D6'><td colspan='3'><input type=button value="�ύ" style='width:80px'  onclick="return tCheck()"></td></tr>
   </table>  
  </div>
  </fieldset>
   </form>
</body>

</html>
<script language="javascript">
<!--
function turnback(){
	window.location.href = window.location.href;	
}
function fCheck(){
	
  if( pwdform.username.value =="" ) {
      alert("\�������û��� !");
      pwdform.username.select();
      pwdform.username.focus();
      return false;
  }
  return true;
}
function tCheck()
{
	if(!fCheck())return false;
	if(document.getElementById('email').value == '�û������ڣ�')
	{
		alert('���û��������ڣ���ȷ�ϣ�');
		return false;
	}
	if(document.getElementById('urltip').innerHTML == 'URL������!')
	{
		alert('��url����ȷ����ȷ�ϣ�');
		return false;
	}
	if(document.getElementById('email').value != "<?php echo $uEmail ?>")
	{
	//	alert('�������ַ������ʵ�����ַ������');
	//	return false;
	}
  	if( pwdform.wurl.value =="" )return false;
	pwdform.submit();
	return true;
}
//����ϸ��Ϣ�ľ�������д��tipDiv��
function displayTip(content) {
    document.getElementById('email').value = content;    
}
function displayUrlTip(content) {
    document.getElementById('urltip').innerHTML = content;    
}

//���ڴ���XMLHttpRequest����
function createXmlHttp() {
    //����window.XMLHttpRequest�����Ƿ����ʹ�ò�ͬ�Ĵ�����ʽ
    if (window.XMLHttpRequest) {
       xmlHttp = new XMLHttpRequest();                  //FireFox��Opera�������֧�ֵĴ�����ʽ
    } else {
       xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");//IE�����֧�ֵĴ�����ʽ
    }
}

//�ӷ��������عؼ��ʵ���ϸ��Ϣ
function loadTip() {
    if(!fCheck())return false;
    displayTip("���ڼ��ء���");                  //��ʾ�����ڼ��ء�������ʾ��Ϣ

    createXmlHttp();                                //����XMLHttpRequest����
    xmlHttp.onreadystatechange = loadTipCallBack;   //���ûص�����
    xmlHttp.open("GET", "../../user/accounts/getusers.php?username=" + pwdform.username.value +"&"+Math.round(Math.random()*100), true);
    xmlHttp.send(null);
}
function checkurl() {

    if( pwdform.wurl.value =="" )return false;
    displayUrlTip("���ڼ��url...");                  //��ʾ�����ڼ��ء�������ʾ��Ϣ

    createXmlHttp();                                //����XMLHttpRequest����
    xmlHttp.onreadystatechange = loadurlCallBack;   //���ûص�����
    xmlHttp.open("GET", "./checkurl.php?wurl=" + pwdform.wurl.value +"&"+Math.round(Math.random()*100), true);
    xmlHttp.send(null);
}
function loadurlCallBack() {
    if (xmlHttp.readyState == 4) {
        displayUrlTip(xmlHttp.responseText);           //��ʾ������ϵ���ϸ��Ϣ
    }
}
//��ȡ��ѯѡ��Ļص�����
function loadTipCallBack() {
    if (xmlHttp.readyState == 4) {
        displayTip(xmlHttp.responseText);           //��ʾ������ϵ���ϸ��Ϣ
    }
}

-->
</script>