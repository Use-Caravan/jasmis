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
          <h1 class="box-title">@lang('admincrud.Admin Profile')</h1>
          <div class="top-action pull-right">
            <a href="{!! route('admin-user.edit',['id' => $model->admin_user_key]) !!}" title="Add New" class="btn mb15"><i class="fa fa-pencil-square-o"></i>@lang('admincommon.Update')</a>
          </div>
        </div> <!--box-header-->

        <div class="box-body">                  
          <div class="table-responsive">
          
            <table class="table table-bordered table-striped" id="enquiryTable">              
              <tbody>
                {{-- <tr>
                    <th>@lang('admincommon.First Name')</th>
                    <td>{{$model->first_name}}</td>
                </tr>
                <tr>
                    <th>@lang('admincommon.Last Name')</th>
                    <td>{{$model->last_name}}</td>
                </tr> --}}
                <tr>
                    <th>@lang('admincommon.User Name')</th>
                    <td>{{$model->username}}</td>
                </tr>
                <tr>
                    <th>@lang('admincommon.Email')</th>
                    <td>{{$model->email}}</td>
                </tr>
                <tr>
                    <th>@lang('admincommon.Phone Number')</th>  
                    <td>{{$model->phone_number}}</td>
                </tr>    
                <tr>
                    <th>@lang('admincrud.Role Name')</th>  
                    <td>{{ ($model->user_type == ADMIN) ? 'Admin' : $model->role_name}}</td>
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