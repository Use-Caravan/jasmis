@extends('admin.layouts.layout')
@section('content')
<div class="content-wrapper">
  <section class="content">
  <div class="col-md-offset-2 col-md-6">
    <div class="box">      
        <div class="box-header with-border">
          <div class="flash-message">
            @if(Session::has('success'))
              <p class="alert alert-success">
                {{ Session('success') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              </p>
            @endif            
          </div> <!-- end .flash-message -->
          <h1 class="box-title">@lang('admincrud.User Management')</h1>
          <div class="top-action pull-right">
            <a href="{!! route('user.edit',['id' => $model->user_key]) !!}" title="Add New" class="btn mb15"><i class="fa fa-pencil-square-o"></i>@lang('admincommon.Update')</a>
          </div>
        </div> <!--box-header-->

        <div class="box-body">
          
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="userTable">              
              <tbody>
                
                <tr>
                    <th>@lang('admincommon.First Name')</th>
                    <td>{{ $model->first_name }}</td>
                </tr>
                <tr>
                    <th>@lang('admincommon.Last Name')</th>
                    <td>{{ $model->last_name }}</td>
                </tr>
                <tr>
                    <th>@lang('admincommon.User Name')</th>
                    <td>{{ $model->username }}</td>
                </tr>
                <tr>
                    <th>@lang('admincommon.Email')</th>
                    <td>{{ $model->email }}</td>
                </tr>                   
                <tr>
                    <th>@lang('admincommon.Phone Number')</th>
                    <td>{{ $model->phone_number }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Profile Image')</th>
                    <td>{!! Html::image($model->profile_image,$model->username,['style'=>'height:50px;']); !!}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Gender')</th>
                    <td>{{ ($model->gender === null) ? 'undefined': common::gender($model->gender)  }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Date of Birth')</th>
                    <td>{{ ($model->dob === null) ? '-' : $model->dob }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Default Language')</th>
                    <td>{{ ($model->default_language) === null ? '-' : $model->default_language }}</td>
                </tr>
                 <tr>
                    <th>@lang('admincrud.Login Type')</th>
                    <td>{{ ($model->login_type === null) ? 'Undefined' : $model->loginTypes($model->login_type) }}</td>
                </tr> 
                @if($model->login_type == LOGIN_TYPE_GP && $model->login_type == LOGIN_TYPE_FB )
                <tr>
                    <th>@lang('admincrud.Social Token')</th>
                    <td>{{ ($model->social_token === null) ? '-' : $model->social_token}}</td>
                </tr>
                @endif
                <tr>
                    <th>@lang('admincrud.Email Verified')</th>
                    <td>{{ ($model->email_verified == YES) ? 'Yes' : 'No' }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.OTP Verified')</th>
                    <td>{{ ($model->otp_verified == YES) ? 'Yes' : 'No' }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Device Type')</th>
                    <td>{{ ($model->login_type === null) ? 'Undefined' : $model->deviceTypes($model->device_type) }}</td>
                </tr> 
                <tr>
                    <th>@lang('admincrud.Device Token')</th>
                    <td>{{ ($model->device_token === null) ? '-' : $model->device_token }}</td>
                </tr>
             
                <tr>
                    <th>@lang('admincrud.Wallet Amount')</th>
                    <td>{{ ($model->wallet_amount === null) ? '-' : $model->wallet_amount }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Loyalty Points')</th>
                    <td>{{ ($model->loyalty_points === null) ? '-' : $model->loyalty_points }}</td>
                </tr>
                <tr>
                    <th>@lang('admincommon.Status')</th>
                    @if($model->status == ITEM_ACTIVE)
                    <td>@lang('admincommon.Active')</td>
                    @elseif($model->status == ITEM_INACTIVE)
                    <td>@lang('admincommon.InActive')</td>
                    @endif
                </tr>
                <tr>
                    <th>@lang('admincrud.Accept Terms And Conditions')</th>
                    <td>{{ ($model->accept_terms_conditions === null) ? '-' : $model->accept_terms_conditions }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          </div>
        </div> <!--box-body-->
    </div> <!--box-->
  </section>
</div> <!--content-wrapper-->

@endsection