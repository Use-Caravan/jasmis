<!DOCTYPE HTML>
<html lang="en-US">
    <head> 
        <meta charset="UTF-8" />
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,600,700" rel="stylesheet" type="text/css"/>
        <style>
            b{font-weight:600}
        </style>
    </head>
<body style="background:#e6e7ea">
<table cellpadding="0" cellspacing="0" width="100%" border="0" align="center" style="padding:25px 15px; font-family: 'Roboto'; font-size:14px; color:#333; line-height:20px;">
<tbody>
 <tr>
   <td width="100%" valign="top">
     <table cellpadding="0" cellspacing="0" border="0" align="center" style="max-width:600px; margin:0 auto; width: 100%;">
       <tbody>
		   <tr>
		     <td align="center" style="padding-bottom:10px">
			   <a href="#"><img src="icons/logo.png" width="140"></a> 
			 </td>
		   </tr>
		   <td>
		     <table cellpadding="0" cellspacing="0" width="100%" border="0" align="center" style="box-shadow: 0px 0px 25px rgba(0, 0, 0, 0.15); border-top-left-radius:8px; border-top-right-radius:8px">
		       <tbody>
			     <tr>
			       <td valign="middle" style="background-color:#fc2217; color:#fff; padding:15px 20px; border-top-left-radius:8px; border-top-right-radius:8px">
				     <h1 style="margin:0px; line-height:normal; font-weight:300; font-size:22px">
					   <b style="font-weight:500">Mail functionality verification</b>
					 </h1>
			       </td>
			     </tr>
				 <tr>
				   <td>
				     <table cellpadding="0" cellspacing="0" width="100%" border="0" align="center" style="background-color:#ffffff; border-left: 1px solid #c1c2c7; border-right: 1px solid #c1c2c7;">
				     <tbody>
					   <tr>
					     <td style="padding:20px 20px 15px 20px;">
                         <p style="margin:0px 0px 10px 0px;">Dear <b> {{config('webconfig.app_name')}} </b>, </p>
							<p style="margin:0px 0px;">The mail for testing purpose only.</p>
					     </td>
					   </tr>
					 </tbody>
				     </table>					 
				   </td>
				 </tr>			    
				 <tr>
			       <td valign="middle" style="background-color:#fc2217; color:#fff; padding:15px 20px; border-bottom-left-radius:8px; border-bottom-right-radius:8px">
				     <table cellpadding="0" cellspacing="0" width="100%" border="0">
						<td><span style="font-weight:300; font-size:13px">Best Regards,</span><br>The {{ config('webconfig.app_name')}} Team.</td>
						<td style="text-align:right; padding-left:15px;">
						  <span style="font-weight:300; font-size:13px">For more information</span><br>
						  <a style="color:#fff; text-decoration:none">{{ config('webconfig.app_email')}}</a>
						</td>
					 </table>
			       </td>
			     </tr>
		       </tbody>
		     </table>
		   </td>
	     </tr>	
		 <tr>
		   <td valign="middle" style="padding:15px 20px; text-align:center">
			 <table cellpadding="0" cellspacing="0" width="100%" border="0">
				<td>
					<p style="margin: 0px 0px 5px 0px; color: #64656b;">Social with us</p>
					<a target="_blank" href="{{ config('webconfig.social_twitter')}}"><img src="{{ asset('resources/assets/icons/facebook.png') }}" style="margin:2px"></a>
                    <a target="_blank" href="{{ config('webconfig.social_facebook')}}"><img src="{{ asset('resources/assets/icons/twitter.png') }}" style="margin:2px"></a>
                    <a target="_blank" href="{{ config('webconfig.social_instagram')}}"><img src="{{ asset('resources/assets/icons/instagram.png') }}" style="margin:2px"></a>
				</td>
			 </table>
		   </td>
		 </tr>			 
       </tbody>
     </table>
   </td>
 </tr>
</tbody>
</table>
</body>
</html>
