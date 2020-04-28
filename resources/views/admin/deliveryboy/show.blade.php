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
          <h1 class="box-title">@lang('admincrud.Delivery Boy Management')</h1>
          <div class="top-action pull-right">
            <a href="{!! route('deliveryboy.edit',['id' => $model->deliveryboy_key]) !!}" title="Add New" class="btn mb15"><i class="fa fa-pencil-square-o"></i>@lang('admincommon.Update')</a>
          </div>
        </div> <!--box-header-->

        <div class="box-body">
          
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="newsletterTable">              
              <tbody>
                
                <tr>
                    <th>@lang('admincrud.Delivery Boy Name')</th>
                    <td>{!! $model->deliveryboy_name !!}</td>
                </tr>
                <tr>
                    <th>@lang('admincommon.Address')</th>
                    <td>{!! $model->address !!}</td>
                </tr>
                <tr>
                    <th>@lang('admincommon.User Name')</th>
                    <td>{!! $model->username !!}</td>
                </tr>
                <tr>
                    <th>@lang('admincommon.Email')</th>
                    <td>{!! $model->email !!}</td>
                </tr>
                <tr>
                    <th>@lang('admincommon.Mobile Number')</th>
                    <td>{!! $model->mobile_number !!}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Commission')</th>
                    <td>{!! $model->commission !!}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Approved Status')</th>
                    <td>{!! ($model->approvedStatus($model->approved_status)) !!}</td>
                </tr>
                <tr>
                    <th>@lang('admincommon.Status')</th>
                    @if($model->status == ITEM_ACTIVE)
                    <td>@lang('admincommon.Active')</td>
                    @elseif($model->status == ITEM_INACTIVE)
                    <td>@lang('admincommon.InActive')</td>
                    @endif
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