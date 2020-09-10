<!DOCTYPE HTML>
<html lang="en-US">
	<head> 
	    <meta charset="UTF-8" />
	    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,600,700" rel="stylesheet" type="text/css"/>
  	</head>
  	<style>
		@media only screen and (min-device-width: 200px) and (max-device-width: 324px) {
			.ord_det{
				font-size: 8px;
			}
		}
		@media only screen and (min-device-width: 325px) and (max-device-width: 400px) {
			.ord_det{
				font-size: 10px;
			}
		}
		@media only screen and (min-device-width: 400px) and (max-device-width: 1024px) {
			.ord_det{
				font-size: 12px;
			}
		}		
	</style>

<body style="background:#ffffff">

<table cellpadding="0" cellspacing="0" border="0" align="center" style="padding:25px 15px; font-family: 'Roboto'; font-size:14px; color:#333; line-height:20px;">
<tbody>
 <tr>
   <td width="100%" valign="top">
     <table cellpadding="0" cellspacing="0" border="0" align="center" style="max-width:600px; margin:0 auto; width: 100%;">
       <tbody>
		   <tr>
		     <td align="center" style="padding-bottom:10px">
			   <!--<a href="{{route('frontend.index')}}"><img src="{{ FileHelper::loadImage(config('webconfig.app_logo')) }}" width="140"></a>--> 
			   <a href="{{route('frontend.index')}}"><img src="{{ url("resources/assets/general/order_confirmation_logo.png") }}" width="140"></a>
			 </td   >
		   </tr>
		   <td>
		   	<hr color="#e22319">
		     <table cellpadding="0" cellspacing="0" width="100%" border="0" align="center" style="box-shadow: 0px 0px 25px rgba(0, 0, 0, 0.15); "><!--border-top-left-radius:8px; border-top-right-radius:8px-->
		       <tbody>
			     <!--<tr>
			       <td valign="middle" style="background-color:#e22319; color:#fff; padding:15px 20px; border-top-left-radius:8px; border-top-right-radius:8px">
				     <h1 style="margin:0px; line-height:normal; font-weight:300; font-size:22px">
					   Thank you for your order!
					 </h1>
			       </td>
			     </tr>-->
				 <tr>
				   <td> 
				     <table cellpadding="0" cellspacing="0" width="100%" border="0" align="center" style="background-color:#ffffff; border-left: 1px solid #c1c2c7; border-right: 1px solid #c1c2c7; border-top: 1px solid #c1c2c7; border-bottom: : 1px solid #c1c2c7;">
				     <tbody>
					   <tr>
					     <td style="padding:20px 20px;">
					     	<p style="margin:0px 0px 10px 0px;">Thank you for your order, <b>Devi Mahendran</b>!</p>
					     	<p style="margin:0px 0px 5px 0px;">Your Caravan will set out soon with your food.</p>
					     	<p style="margin:0px 0px 5px 0px;">Here are your order details: </p>
					     </td>
					    </tr>

						<!--<tr class="ord_det">
					   		<td class="sample" style="padding:0% 5% 0% 10%;"><div style=" display: grid; grid-template-columns: auto auto auto;padding: 10px;"> <span>Order ID:</span>    <span style="padding:0px 0px 5% 0px;">#CRN0000476</span></div></td>
					   	</tr>

					   	<tr class="ord_det">
					   		<td class="sample" style="padding:0% 5% 0% 10%;"><div style=" display: grid; grid-template-columns: auto auto auto;padding: 10px;"> <span>Time of Order:</span>    <span style="padding:0px 0px 5% 0px;">10-09-2020 12:09 PM</span></div></td>
					   	</tr>-->

					   	<!--<tr class="ord_det">
					   		<td style="padding:0% 5% 0% 10%;">Order ID: </td><td style="padding:0% 5% 0% 10%;">#CRN0000476</td>
					   	</tr>

					   	<tr class="ord_det">
					   		<td style="padding:3% 5% 0% 10%;">Time of Order: </td><td style="padding:3% 5% 0% 10%;">10-09-2020 12:09 PM</td>
					   	</tr>

					   	<tr class="ord_det">
					   		<td><span style="padding:3% 5% 0% 10%;">Time of Order: </span><span style="padding:0px 0px 5% 30px;">10-09-2020 12:09 PM</span></td>
					   	</tr>-->

					   	<table class="ord_det">
						   	<tr> 
					            <td width="20%" style="padding:0% 0% 0% 10%;">Order ID:</td> 
					            <td width="20%">#CRN0000476</td> 
					        </tr> 
					  
					        <tr> 
					            <td width="20%" style="padding:0% 0% 0% 10%;">Time of Order:</td> 
					            <td width="20%">10-09-2020 12:09 PM</td> 
					        </tr>

					        <tr> 
					            <td width="20%" style="padding:0% 0% 0% 10%;">Payment Method:</td> 
					            <td width="20%">Cash On Delivery</td> 
					        </tr> 
					  
					        <tr> 
					            <td width="20%" style="padding:0% 0% 0% 10%;">Delivery Address:</td> 
					            <td width="20%">Near dubai busstand, Dhayasaran, Apartment : AP1, Building : 1st, Street Name: laliroad bus stop, Floor : 2nd, Block : 2nd, Area : laliroad</td> 
					        </tr>
					    </table>


					   	<!--<tr class="ord_det">
					   		<td class="sample"><span style="padding:0% 0% 0% 10%;width: 20%;">Order ID:</span><span style="padding:0% 0% 0% 5%;">#CRN0000476</span></td>
					   	</tr>

					   	<tr class="ord_det">
					   		<td class="sample"> <span style="padding:0% 0% 0% 20%;width: 20%;display: block;">Time of Order:</span><span style="padding:0% 0% 0% 5%;">10-09-2020 12:09 PM</span></td>
					   	</tr>

					   	<tr class="ord_det">
					   		<td class="sample"><div style="display: grid; grid-template-columns: auto auto auto;padding: 5px;"> <span style="padding:0% 5% 0% 20%;">Payment Method:</span><span style="padding:0% 0% 0% 5%;">Cash On Delivery</span></div></td>
					   	</tr>

					   	<tr class="ord_det">
					   		<td class="sample"><div style="display: grid; grid-template-columns: auto auto auto;padding: 5px;"> <span style="padding:0% 5% 0% 20%;">Delivery Address:</span><span style="padding:0% 0% 0% 5%;">Near dubai busstand, Dhayasaran, Apartment : AP1, Building : 1st, Street Name: laliroad bus stop, Floor : 2nd, Block : 2nd, Area : laliroad</span></div></td>
					   	</tr>

					   	<tr class="ord_det">
					   		<td style="padding:0% 5% 0% 10%;">Order ID: <span style="padding:0px 0px 5% 30px;">#CRN0000476</span></td>
					   	</tr>
					   	
					   	<tr class="ord_det">
					   		<td style="padding:3% 5% 0% 10%;">Time of Order: <span style="padding:0px 0px 5% 30px;">10-09-2020 12:09 PM</span></td>
					   	</tr>
					   	<tr class="ord_det">
					   		<td style="padding:3% 5% 0% 10%;">Payment Method: <span style="padding:0px 0px 5% 10px;">Cash On Delivery</span></td>
					   	</tr>
					   	<tr class="ord_det">
					   		<td style="padding:3% 5% 5% 10%;">Delivery Address: <span style="padding:0px 0px 5% 10px;"> 
								Near dubai busstand, Dhayasaran, Apartment : AP1, Building : 1st, Street Name: laliroad bus stop, Floor : 2nd, Block : 2nd, Area : laliroad</span>
							</td>
					   	</tr>	-->




						  <tr>
						    <td>
						      <table cellpadding="0" cellspacing="0" width="100%" border="0" style="margin-bottom:20px; border-top: 1px solid #d3d4d8;">
							  <tr>
							    <td>
								<table cellpadding="0" cellspacing="0" width="100%" border="0" >
								<tr style="color:#fff;">
								  <thead style="background:#e22319;">
					               <th style="padding:10px 15px; text-align:left; font-weight:500;color:#fff;width: 36%;">Item Name</th>
					               <th style="padding:10px 0px; text-align:center; font-weight:500;color:#fff;">Qty</th>
					               <th style="padding:10px 15px; text-align:right; font-weight:500;color:#fff;">Price</th>
								  <thead>
								</tr>
                                 
                                <tr style="background:#fff;">
								  <td style="padding:12px 15px; border-bottom:1px solid #e1e2e4; border-left:1px solid #d3d4d8;">
								   <p style="margin:0 0 5px 0px; font-weight:500">Egg Muffin With Sauce</p>
								   
								  </td>
								  <td width="50" style="padding:12px 0px; text-align:center; border-bottom:1px solid #e1e2e4;">1</td>
								  <td width="80" style="padding:12px 15px; text-align:right; border-bottom:1px solid #e1e2e4; border-right:1px solid #d3d4d8;">BD 1.400</td>					
								</tr>
                                <tr style="text-align:right; font-weight:500; font-size:13px">
								  <td style="border-right:1px solid #d3d4d8;"></td>
								  <td width="100" style="padding:8px 15px; border-bottom:1px solid #d3d4d8;">Sub Total:</td>
								  <td width="100" style="padding:8px 15px; border-bottom:1px solid #d3d4d8; border-right:1px solid #d3d4d8;">BD 1.400</td>					
								</tr>
								<tr style="text-align:right; font-weight:500; font-size:13px">
								  <td style="border-right:1px solid #d3d4d8;"></td>
								  <td width="100" style="padding:8px 15px; border-bottom:1px solid #d3d4d8;">Delivery Charge:</td>
								  <td width="100" style="padding:8px 15px; border-bottom:1px solid #d3d4d8; border-right:1px solid #d3d4d8;">BD 0.000</td>					
								</tr> 
                                <tr style="text-align:right; font-weight:500; color:#fff; font-size:13px">
								  <td style="border-right:1px solid #e22319;"></td>
								  <td width="80" style="padding:12px 15px; background:#e22319">Grand Total</td>
								  <td width="80" style="padding:12px 15px; background:#e22319; font-size:20px">BD 1.400</td>
								</tr>
								</table>
								</td>
							</tr>
							</table>
							</td>							
						  </tr>

						<tr>
						   	<td> 
						     	<table cellpadding="0" cellspacing="0" width="100%" border="0" align="center">
						     		<!--style="background-color:#ffffff; border-left: 1px solid #c1c2c7; border-right: 1px solid #c1c2c7;"-->
						     		<tbody>
									   	<tr>
									     	<td style="padding:20px 20px;">
										     	<p style="margin:0px 0px 10px 0px;text-align: center;">If you have any enquiries, feel free to contact us any time</p>
										     	<p style="margin:0px 0px 5px 0px;text-align: center;">Email at support@usecaravan.com.</p>
										     	<p style="margin:25px 0px 5px 0px;text-align: center;">Until next time, </p>
										     	<p style="margin:0px 0px 5px 0px;text-align: center;">Your Caravan Team</p>
									     	</td>
									   	</tr>
									</tbody>
								</table>
							</td>
						</tr>

						<tr style="text-align:center; font-weight:500; color:#fff; font-size:13px">
						  	<td style="padding:12px 15px; background:#e22319;">"Put us in your pocket and get your Caravan on the move."</td>					
						</tr>

						<tr style="text-align:center; font-size:13px">
						  	<td style="padding:12px 15px;">@<?php echo date("Y");?> Caravan All Rights Reserved.</td>					
						</tr>
					 </tbody>
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
