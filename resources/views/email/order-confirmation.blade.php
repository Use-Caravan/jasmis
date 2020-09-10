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

<table cellpadding="0" cellspacing="0" width="100%" border="0" align="center" style="padding:25px 15px; font-family: 'Roboto'; font-size:14px; color:#333; line-height:20px;">
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
					     	<p style="margin:0px 0px 10px 0px;">Thank you for your order, <b>{{ucfirst($order_details['orderdetails']->first_name)." ".ucfirst($order_details['orderdetails']->last_name)}}</b>!</p>
					     	<p style="margin:0px 0px 5px 0px;">Your Caravan will set out soon with your food.</p>
					     	<p style="margin:0px 0px 5px 0px;">Here are your order details: </p>

							<!--<p style="margin:0px 0px 10px 0px;">Dear <b>{{$order_details['orderdetails']->first_name.$order_details['orderdetails']->last_name}}</b>, </p>
							<p style="margin:0px 0px 5px 0px;">Thank you for placing order at <b>{{$order_details['orderitems']->data->branch_name}}</b>.</p>
							Exciting to have you here. Your order has been successfully placed and please allow us sometime to confirm your order.-->
					     </td>
					   </tr>

					   	<table class="ord_det">
						   	<tr> 
					            <td width="20%" style="padding:0% 0% 0% 10%;">Order ID:</td> 
					            <td width="20%">{{ $order_details['orderitems']->data->order_number }}</td> 
					        </tr> 
					  
					        <tr> 
					            <td width="20%" style="padding:0% 0% 0% 10%;">Time of Order:</td> 
					            <td width="20%">{{ $order_details['orderitems']->data->order_datetime }}</td> 
					        </tr>

					        <tr> 
					            <td width="20%" style="padding:0% 0% 0% 10%;">Payment Method:</td> 
					            <td width="20%">{{$modelOrder->paymentTypes($order_details['orderdetails']->payment_type)}}</td>
					        </tr> 
					  
					        <tr> 
					            <td width="20%" style="padding:0% 0% 0% 10%;">Delivery Address:</td> 
					            <td width="20%">{{$order_details['address']}}</td> 
					        </tr>
					    </table>

					    <!--<tr>
					   		<td style="padding:0% 5% 0% 10%;">Order ID: <span style="padding:0px 0px 5% 65px;">{{ $order_details['orderitems']->data->order_number }}</span></td>
					   	</tr>
					   	<tr>
					   		<td style="padding:3% 5% 0% 10%;">Time of Order: <span style="padding:0px 0px 5% 30px;">{{ $order_details['orderitems']->data->order_datetime }}</span></td>
					   	</tr>
					   	<tr>
					   		<td style="padding:3% 5% 0% 10%;">Payment Method: <span style="padding:0px 0px 5% 10px;">    {{$modelOrder->paymentTypes($order_details['orderdetails']->payment_type)}}</span></td>
					   	</tr>
					   	<tr>
					   		<td style="padding:3% 5% 5% 10%;">Delivery Address: <span style="padding:0px 0px 5% 10px;"> 
								{{$order_details['address']}}</span>
							</td>
					   	</tr>-->

					   <!--<tr valign="top">
						 <table cellpadding="0" cellspacing="0" width="100%" border="0" style="background-color:#f2f2f2; border: 1px solid #c1c2c7; border-top: 0px; border-bottom: 0px; padding:20px 20px">
						  <tr>
						    <td>
						      <table cellpadding="0" cellspacing="0" width="100%" border="0" style="background-color:#fff; margin-bottom:20px; border: 1px solid #d3d4d8;">
							  <tr>
							   <th colspan="3" style="padding:15px 15px 0px 15px; text-align:left; color:#e22319; font-size:14px; font-weight:600; text-transform:uppercase">
							   Order Information:
							   </th>							   
							  </tr>
							  <tr>
							    <td>
								<table cellpadding="0" cellspacing="0" width="100%" border="0">
					            <td style="padding:15px; text-align:left;">
							      <p style="margin:0px 0px 4px 0px; text-transform:uppercase; color:#7d7d7d; font-size:13px">
								  Order ID
								  </p>
						   	      <b style="font-size:20px; font-weight:500">{{ $order_details['orderitems']->data->order_number }}</b>
					            </td>
					            <td style="padding:15px 0px; text-align:right">
							      <p style="margin:0px 0px 5px 0px; text-transform:uppercase; color:#7d7d7d; font-size:13px">
								  Date Added
								  </p>
							      <b style="font-weight:500">{{ $order_details['orderitems']->data->order_datetime }}</b>
					            </td>
                                @if($order_details['orderdetails']->order_booked_by === USER_TYPE_CUSTOMER)
                                    <td style="padding:15px; text-align:right">
                                    <p style="margin:0px 0px 5px 0px; text-transform:uppercase; color:#7d7d7d; font-size:13px">
                                    Payment Method
                                    </p>
                                    <b style="font-weight:500">{{$modelOrder->paymentTypes($order_details['orderdetails']->payment_type)}}</b>
                                    </td>
                                @endif
								</table>
								</td>
							</tr>
							</table>
							</td>							
						  </tr>-->
						  <tr>
						    <td>
						      <table cellpadding="0" cellspacing="0" width="100%" border="0" style="margin-bottom:20px; border-top: 1px solid #d3d4d8;">
							  <!--<tr>
							   <td>
							    <table cellpadding="0" cellspacing="0" width="100%" border="0">
							     <thead>
							       <th colspan="3" style="padding:10px 15px 10px 15px; text-align:left; color:#e22319; font-size:14px; font-weight:600; text-transform:uppercase; background-color:#fff; border-left: 1px solid #d3d4d8; border-right: 1px solid #d3d4d8;">
								   Order Detail:
								   </th>							   
							     </thead>
							    </table>
							   <td> 
							  </tr>-->
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
                                 
                                @foreach($order_details['orderitems']->data->items as $key => $value)
                                <tr style="background:#fff;">
								  <td style="padding:12px 15px; border-bottom:1px solid #e1e2e4; border-left:1px solid #d3d4d8;">
								   <p style="margin:0 0 5px 0px; font-weight:500">{{$value->item_name}}</p>
								   
								  </td>
								  <td width="50" style="padding:12px 0px; text-align:center; border-bottom:1px solid #e1e2e4;">{{$value->item_quantity}}</td>
								  <td width="80" style="padding:12px 15px; text-align:right; border-bottom:1px solid #e1e2e4; border-right:1px solid #d3d4d8;">{{$value->item_subtotal}}</td>					
								</tr>
                                @endforeach
                                @foreach($order_details['orderitems']->data->payment_details as $key => $value)
								<tr style="text-align:right; font-weight:500; font-size:13px">
								  <td style="border-right:1px solid #d3d4d8;"></td>
								  <td width="100" style="padding:8px 15px; border-bottom:1px solid #d3d4d8;">{{$value->name}}:</td>
								  <td width="100" style="padding:8px 15px; border-bottom:1px solid #d3d4d8; border-right:1px solid #d3d4d8;">{{$value->price}}</td>					
								</tr> 
                                @endforeach
								<tr style="text-align:right; font-weight:500; color:#fff; font-size:13px">
								  <td style="border-right:1px solid #e22319;"></td>
								  <td width="80" style="padding:12px 15px; background:#e22319">Grand Total</td>
								  <td width="80" style="padding:12px 15px; background:#e22319; font-size:20px">{{$order_details['orderitems']->data->total_amount->price}}</td>					
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
								  

						  <!--<tr>
						    <td>
						      <table cellpadding="0" cellspacing="0" width="100%" border="0">
							  <tr>
								<td style="background-color:#fff; border: 1px solid #d3d4d8; width:300px">
								  <table cellpadding="0" cellspacing="0" width="100%" border="0">
								    <tr>
									   <th style="padding:15px 15px 0px 15px; text-align:left; color:#e22319; font-size:14px; font-weight:600; text-transform:uppercase">
									   Customer Information:
									   </th>							   
									</tr>
									<tr>
										<td style="padding:15px 15px">
										<b>Email:</b> {{$order_details['orderdetails']->email}}<br>
										<b>Phone:</b> {{$order_details['orderdetails']->user_phone_number}}<br>
										</td>
									</tr>
								  </table>
								</td>
								<td width="20"></td>
								<td style="background-color:#fff; border: 1px solid #d3d4d8; width:300px">
								  <table cellpadding="0" cellspacing="0" width="100%" border="0">
								    <tr>
									   <th style="padding:15px 15px 0px 15px; text-align:left; color:#e22319; font-size:14px; font-weight:600; text-transform:uppercase">
									   Shipping Address:
									   </th>							   
									</tr>
									<tr>
										<td style="padding:15px 15px">
										{{$order_details['orderdetails']->address_line_one}}<br>
										{{$order_details['orderdetails']->address_line_two}}.<br>
										</td>
									</tr>
								  </table>
								</td>
							  </tr>							  
							</table>
							</td>							
						  </tr>
						 </table>						 
					   </tr>-->
					 </tbody>
				     </table>
				   </td>
				 </tr>
			    <!--<tr>
				    <td style="padding:15px 20px; background:#fff; text-align:center; border: 1px solid #c1c2c7; border-top: 0px; border-bottom: 0px;">
                        <p style="margin:0px 0px 5px 0px; font-weight:500; color:#e22319">
                            <span style="text-align:right; display:inline-block">"People who love to eat are always the best people"<br>
                            <span style="color: #333; font-weight: normal;">- Julia Child</span></span>
                        </p>
				    </td>   
                </tr>
                @if($order_details['orderdetails']->order_booked_by === USER_TYPE_CORPORATES)
                    @if($order_details['need_voucher_url'] !== null || $order_details['need_voucher_url'] !== '')
                    <tr>
                        <td style="padding:15px 20px; background:#fff; text-align:center; border: 1px solid #c1c2c7; border-top: 0px; border-bottom: 0px;">
                            <a href="{{ url($order_details['need_voucher_url']) }}" style="margin:0px 0px 5px 0px; font-weight:500; color:#0000FF"> {{-- e22319 --}}
                                <span style="text-align:right; display:inline-block text-decoration: underline"> Please collect your vouchers here </span>
                            </a>
                        </td>
                    </tr>
                    @endif
                @endif
				 <tr>
			       <td valign="middle" style="background-color:#e22319; color:#fff; padding:15px 20px; border-bottom-left-radius:8px; border-bottom-right-radius:8px">
				     <table cellpadding="0" cellspacing="0" width="100%" border="0">
						<td><span style="font-weight:300; font-size:13px">Best Regards,</span><br>{{$order_details['orderitems']->data->branch_name}} Team.</td>
						<td style="text-align:right; padding-left:15px;">
						  <span style="font-weight:300; font-size:13px">For more information</span><br>
						  <a style="color:#fff; text-decoration:none">{{ config('webconfig.app_email') }}</a>
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
					<a href="{{ config('webconfig.social_facebook') }}"><img src="{{url('resources/assets/icons/facebook.png')}}" style="margin:2px"></a>
					<a href="{{ config('webconfig.social_twitter') }}"><img src="{{url('resources/assets/icons/twitter.png')}}" style="margin:2px"></a>
					<a href="{{ config('webconfig.social_instagram') }}"><img src="{{url('resources/assets/icons/instagram.png')}}" style="margin:2px"></a>
				</td>
			 </table>
		   </td>
		 </tr>-->
       </tbody>
     </table>
   </td>
 </tr>
</tbody>
</table>

</body>
</html>
