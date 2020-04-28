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
          <h1 class="box-title">@lang('admincrud.Vendor Management')</h1>
          <div class="top-action pull-right">
            <a href="{!! route('vendor.edit',['id' => $modelShow->vendor_key]) !!}" title="Add New" class="btn mb15"><i class="fa fa-pencil-square-o"></i>@lang('admincommon.Update')</a>
          </div>
        </div> <!--box-header-->

        <div class="box-body">
          
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="newsletterTable">              
              <tbody>
                
                <tr>
                    <th>@lang('admincrud.Vendor Name')</th>
                    <td>{{ $modelShow->vendor_name }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Vendor Logo')</th>
                    <td>{!! Html::image($modelShow->vendor_logo,$modelShow->vendor_name,['style'=>'height:50px;']); !!}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Vendor Description')</th>
                    <td>{{ $modelShow->vendor_description }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Vendor Address')</th>
                    <td>{{ $modelShow->vendor_address }}</td>
                </tr>                   
                 <tr>
                    <th>@lang('admincommon.User Name')</th>
                    <td>{{ $modelShow->username }}</td>
                </tr>
                <tr>
                    <th>@lang('admincommon.Email')</th>
                    <td>{{ $modelShow->email }}</td>
                </tr>
                <tr>
                    <th>@lang('admincommon.Mobile Number')</th>
                    <td>{{ $modelShow->mobile_number }}</td>
                </tr>
                <tr>
                    <th>@lang('admincommon.Contact Number')</th>
                    <td>{{ $modelShow->contact_number }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Country Name')</th>
                    <td>{{ $modelShow->country_name }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.City Name')</th>
                    <td>{{ $modelShow->city_name }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Area Name')</th>
                    <td>{{ $modelShow->area_name }}</td>
                </tr>
                 <tr>
                    <th>@lang('admincrud.Tax')</th>
                    <td>{{ $modelShow->tax }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Service Tax')</th>
                    <td>{{ $modelShow->service_tax }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Min Order Value')</th>
                    <td>{{ $modelShow->min_order_value }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Service Tax')</th>
                    <td>{{ $modelShow->sort_no }}</td>
                </tr>
                <tr>
                   @php $explodePayment=explode(',',$modelShow->payment_option);@endphp
                   <th>@lang('admincrud.Payment Option')</th>
                    <td>@foreach($explodePayment as $key => $value) {{ ($value === null) ? 'undefined': $modelOrder->paymentTypes($value) }},@endforeach</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Commission Type')</th>
                    <td>{{ ($modelShow->commission_type === null) ? 'undefined': $modelShow->commissionTypes($modelShow->commission_type) }}</td>
                </tr>
                <tr> 
                    <th>@lang('admincrud.Approved Status')</th>
                    <td>{{ ($modelShow->approved_status === null) ? 'undefined' : $modelShow->approvedStatus($modelShow->approved_status) }}</td>
                </tr>
                <tr>
                    <th>@lang('admincommon.Status')</th>
                    @if($modelShow->status == ITEM_ACTIVE)
                    <td>@lang('admincommon.Active')</td>
                    @elseif($modelShow->status == ITEM_INACTIVE)
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