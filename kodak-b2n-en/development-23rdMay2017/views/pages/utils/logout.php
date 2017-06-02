<rn:meta title="#rn:msg:SUPPORT_LOGIN_HDG#" template="iframekodak_b2b_template.php" login_required="false" />
			  <script>
  function ajaxRequest(){
 var activexmodes=["Msxml2.XMLHTTP", "Microsoft.XMLHTTP"] //activeX versions to check for in IE
 if (window.ActiveXObject){ //Test for support for ActiveXObject in IE first (as XMLHttpRequest in IE7 is broken)
  for (var i=0; i<activexmodes.length; i++){
   try{
    return new ActiveXObject(activexmodes[i])
   }
   catch(e){
    //suppress error
   }
  }
 }
 else if (window.XMLHttpRequest) // if Mozilla, Safari etc
  return new XMLHttpRequest()
 else
  return false
}
var mygetrequest=new ajaxRequest()
mygetrequest.onreadystatechange=function(){
 if (mygetrequest.readyState==4){
  if (mygetrequest.status==200 || window.location.href.indexOf("http")==-1){
   document.getElementById("result").innerHTML=mygetrequest.responseText
  }
  else{
   alert("An error has occured making the request")
  }
 }
}
//mygetrequest.open("GET", "/ci/ajaxRequest/doLogout", true)
//mygetrequest.send(null);



			  </script>
<div id="rn_PageContent" class="rn_Account">
			  <div id="result" style="display:none"> </div>
<rn:condition url_parameter_check="return_to != null">
            <rn:widget path="custom/login/MyLogout" redirect_url="#rn:url_param_value:return_to#" />
<rn:condition_else>							
            <rn:widget path="custom/login/MyLogout" redirect_url="/" />
</rn:condition>

     </div>
	 
