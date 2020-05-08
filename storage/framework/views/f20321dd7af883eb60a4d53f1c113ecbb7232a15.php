<!DOCTYPE HTML>
<html lang="en-US">
  <head> 
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,600,700" rel="stylesheet" type="text/css"/>
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
			   <a href="<?php echo e(route('frontend.index')); ?>"><img src="<?php echo e(FileHelper::loadImage(config('webconfig.app_logo'))); ?>" width="140"></a> 
			 </td   >
		   </tr>
		   <td>
		     <table cellpadding="0" cellspacing="0" width="100%" border="0" align="center" style="box-shadow: 0px 0px 25px rgba(0, 0, 0, 0.15); border-top-left-radius:8px; border-top-right-radius:8px">
		       <tbody>
			     <tr>
			       <td valign="middle" style="background-color:#fc2217; color:#fff; padding:15px 20px; border-top-left-radius:8px; border-top-right-radius:8px">
				     <h1 style="margin:0px; line-height:normal; font-weight:300; font-size:22px">
					   Thank you for your order!
					 </h1>
			       </td>
			     </tr>
				 <tr>
				   <td> 
				     <table cellpadding="0" cellspacing="0" width="100%" border="0" align="center" style="background-color:#ffffff; border-left: 1px solid #c1c2c7; border-right: 1px solid #c1c2c7;">
				     <tbody>
					   <tr>
					     <td style="padding:20px 20px;">
							<p style="margin:0px 0px 10px 0px;">Dear <b><?php echo e($order_details['orderdetails']->first_name.$order_details['orderdetails']->last_name); ?></b>, </p>
							<p style="margin:0px 0px 5px 0px;">Thank you for placing order at <b><?php echo e($order_details['orderitems']->data->branch_name); ?></b>.</p>
							Exciting to have you here. Your order has been successfully placed and please allow us sometime to confirm your order.
					     </td>
					   </tr>
					   <tr valign="top">
						 <table cellpadding="0" cellspacing="0" width="100%" border="0" style="background-color:#f2f2f2; border: 1px solid #c1c2c7; border-top: 0px; border-bottom: 0px; padding:20px 20px">
						  <tr>
						    <td>
						      <table cellpadding="0" cellspacing="0" width="100%" border="0" style="background-color:#fff; margin-bottom:20px; border: 1px solid #d3d4d8;">
							  <tr>
							   <th colspan="3" style="padding:15px 15px 0px 15px; text-align:left; color:#fc2217; font-size:14px; font-weight:600; text-transform:uppercase">
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
						   	      <b style="font-size:20px; font-weight:500"><?php echo e($order_details['orderitems']->data->order_number); ?></b>
					            </td>
					            <td style="padding:15px 0px; text-align:right">
							      <p style="margin:0px 0px 5px 0px; text-transform:uppercase; color:#7d7d7d; font-size:13px">
								  Date Added
								  </p>
							      <b style="font-weight:500"><?php echo e($order_details['orderitems']->data->order_datetime); ?></b>
					            </td>
                                <?php if($order_details['orderdetails']->order_booked_by === USER_TYPE_CUSTOMER): ?>
                                    <td style="padding:15px; text-align:right">
                                    <p style="margin:0px 0px 5px 0px; text-transform:uppercase; color:#7d7d7d; font-size:13px">
                                    Payment Method
                                    </p>
                                    <b style="font-weight:500"><?php echo e($modelOrder->paymentTypes($order_details['orderdetails']->payment_type)); ?></b>
                                    </td>
                                <?php endif; ?>
								</table>
								</td>
							</tr>
							</table>
							</td>							
						  </tr>
						  <tr>
						    <td>
						      <table cellpadding="0" cellspacing="0" width="100%" border="0" style="margin-bottom:20px; border-top: 1px solid #d3d4d8;">
							  <tr>
							   <td>
							    <table cellpadding="0" cellspacing="0" width="100%" border="0">
							     <thead>
							       <th colspan="3" style="padding:10px 15px 10px 15px; text-align:left; color:#fc2217; font-size:14px; font-weight:600; text-transform:uppercase; background-color:#fff; border-left: 1px solid #d3d4d8; border-right: 1px solid #d3d4d8;">
								   Order Detail:
								   </th>							   
							     </thead>
							    </table>
							   <td> 
							  </tr>
							  <tr>
							    <td>
								<table cellpadding="0" cellspacing="0" width="100%" border="0" >
								<tr style="">
								  <thead style="background:#e1e2e4; text-transform:uppercase;">
					               <th style="padding:10px 15px; text-align:left; font-weight:500">Product</th>
					               <th style="padding:10px 0px; text-align:center; font-weight:500">Qty</th>
					               <th style="padding:10px 15px; text-align:right; font-weight:500">Price</th>
								  <thead>
								</tr>
                                 
                                <?php $__currentLoopData = $order_details['orderitems']->data->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr style="background:#fff;">
								  <td style="padding:12px 15px; border-bottom:1px solid #e1e2e4; border-left:1px solid #d3d4d8;">
								   <p style="margin:0 0 5px 0px; font-weight:500"><?php echo e($value->item_name); ?></p>
								   
								  </td>
								  <td width="50" style="padding:12px 0px; text-align:center; border-bottom:1px solid #e1e2e4;"><?php echo e($value->item_quantity); ?></td>
								  <td width="80" style="padding:12px 15px; text-align:right; border-bottom:1px solid #e1e2e4; border-right:1px solid #d3d4d8;"><?php echo e($value->item_subtotal); ?></td>					
								</tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php $__currentLoopData = $order_details['orderitems']->data->payment_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<tr style="text-align:right; font-weight:500; text-transform:uppercase; font-size:13px">
								  <td style="border-right:1px solid #d3d4d8;"></td>
								  <td width="80" style="padding:8px 15px; border-bottom:1px solid #d3d4d8; background:#e6e7ea"><?php echo e($value->name); ?>:</td>
								  <td width="80" style="padding:8px 15px; border-bottom:1px solid #d3d4d8; border-right:1px solid #d3d4d8; background:#e6e7ea"><?php echo e($value->price); ?></td>					
								</tr> 
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								<tr style="text-align:right; font-weight:500; color:#fff; text-transform:uppercase; font-size:13px">
								  <td style="border-right:1px solid #fc2217;"></td>
								  <td width="80" style="padding:12px 15px; background:#fc2217">Total</td>
								  <td width="80" style="padding:12px 15px; background:#fc2217; font-size:20px"><?php echo e($order_details['orderitems']->data->total_amount->price); ?></td>					
								</tr>
								</table>
								</td>
							</tr>
							</table>
							</td>							
						  </tr>
						  <tr>
						    <td>
						      <table cellpadding="0" cellspacing="0" width="100%" border="0">
							  <tr>
								<td style="background-color:#fff; border: 1px solid #d3d4d8; width:300px">
								  <table cellpadding="0" cellspacing="0" width="100%" border="0">
								    <tr>
									   <th style="padding:15px 15px 0px 15px; text-align:left; color:#fc2217; font-size:14px; font-weight:600; text-transform:uppercase">
									   Customer Information:
									   </th>							   
									</tr>
									<tr>
										<td style="padding:15px 15px">
										<b>Email:</b> <?php echo e($order_details['orderdetails']->email); ?><br>
										<b>Phone:</b> <?php echo e($order_details['orderdetails']->user_phone_number); ?><br>
										</td>
									</tr>
								  </table>
								</td>
								<td width="20"></td>
								<td style="background-color:#fff; border: 1px solid #d3d4d8; width:300px">
								  <table cellpadding="0" cellspacing="0" width="100%" border="0">
								    <tr>
									   <th style="padding:15px 15px 0px 15px; text-align:left; color:#fc2217; font-size:14px; font-weight:600; text-transform:uppercase">
									   Shipping Address:
									   </th>							   
									</tr>
									<tr>
										<td style="padding:15px 15px">
										<?php echo e($order_details['orderdetails']->address_line_one); ?><br>
										<?php echo e($order_details['orderdetails']->address_line_two); ?>.<br>
										</td>
									</tr>
								  </table>
								</td>
							  </tr>							  
							</table>
							</td>							
						  </tr>
						 </table>						 
					   </tr>
					 </tbody>
				     </table>
				   </td>
				 </tr>
			    <tr>
				    <td style="padding:15px 20px; background:#fff; text-align:center; border: 1px solid #c1c2c7; border-top: 0px; border-bottom: 0px;">
                        <p style="margin:0px 0px 5px 0px; font-weight:500; color:#fc2217">
                            <span style="text-align:right; display:inline-block">"People who love to eat are always the best people"<br>
                            <span style="color: #333; font-weight: normal;">- Julia Child</span></span>
                        </p>
				    </td>   
                </tr>
                <?php if($order_details['orderdetails']->order_booked_by === USER_TYPE_CORPORATES): ?>
                    <?php if($order_details['need_voucher_url'] !== null || $order_details['need_voucher_url'] !== ''): ?>
                    <tr>
                        <td style="padding:15px 20px; background:#fff; text-align:center; border: 1px solid #c1c2c7; border-top: 0px; border-bottom: 0px;">
                            <a href="<?php echo e(url($order_details['need_voucher_url'])); ?>" style="margin:0px 0px 5px 0px; font-weight:500; color:#0000FF"> 
                                <span style="text-align:right; display:inline-block text-decoration: underline"> Please collect your vouchers here </span>
                            </a>
                        </td>
                    </tr>
                    <?php endif; ?>
                <?php endif; ?>
				 <tr>
			       <td valign="middle" style="background-color:#fc2217; color:#fff; padding:15px 20px; border-bottom-left-radius:8px; border-bottom-right-radius:8px">
				     <table cellpadding="0" cellspacing="0" width="100%" border="0">
						<td><span style="font-weight:300; font-size:13px">Best Regards,</span><br><?php echo e($order_details['orderitems']->data->branch_name); ?> Team.</td>
						<td style="text-align:right; padding-left:15px;">
						  <span style="font-weight:300; font-size:13px">For more information</span><br>
						  <a style="color:#fff; text-decoration:none"><?php echo e(config('webconfig.app_email')); ?></a>
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
					<a href="<?php echo e(config('webconfig.social_facebook')); ?>"><img src="<?php echo e(url('resources/assets/icons/facebook.png')); ?>" style="margin:2px"></a>
					<a href="<?php echo e(config('webconfig.social_twitter')); ?>"><img src="<?php echo e(url('resources/assets/icons/twitter.png')); ?>" style="margin:2px"></a>
					<a href="<?php echo e(config('webconfig.social_instagram')); ?>"><img src="<?php echo e(url('resources/assets/icons/instagram.png')); ?>" style="margin:2px"></a>
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
