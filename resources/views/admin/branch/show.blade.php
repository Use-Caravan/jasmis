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
          <h1 class="box-title">@lang('admincrud.Branch Management')</h1>
          <div class="top-action pull-right">
            <a href="{!! route('branch.edit',['id' => $modelShow->branch_key]) !!}" title="Add New" class="btn mb15"><i class="fa fa-pencil-square-o"></i>@lang('admincommon.Update')</a>
          </div>
        </div> <!--box-header-->

        <div class="box-body">
          
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="newsletterTable">              
              <tbody>
                
                <tr>
                    <th>@lang('admincrud.Branch Name')</th>
                    <td>{{ $modelShow->branch_name }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Vendor Name')</th>
                    <td>{{ $modelShow->vendor_name }}</td>
                </tr>
                {{-- <tr>
                    <th>@lang('admincrud.Branch Logo')</th>
                    <td>{!! Html::image($modelShow->branch_logo,$modelShow->branch_name,['style'=>'height:50px;']); !!}</td>
                </tr> --}}
                <tr>
                    <th>@lang('admincrud.Branch Address')</th>
                    <td>{{ $modelShow->branch_address }}</td>
                </tr>                   
                <tr>
                    <th>@lang('admincommon.Email')</th>
                    <td>{{ $modelShow->contact_email }}</td>
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
                    <th>@lang('admincrud.Category Name')</th>
                    <td>{{ $modelShow->category_names }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Cuisine Name')</th>
                    <td>{{ $modelShow->cuisine_names }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Delivery Area Name')</th>
                    <td>{{ $modelShow->delivery_area_names }}</td>
                </tr>
                <tr>

                <tr>
                    <th>@lang('admincrud.Restaurant Type')</th>
                    <td>{{ ($modelShow->restaurant_type === null) ? 'undefined': $vendor->restaurantTypes($modelShow->restaurant_type) }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Order Type')</th>
                    <td>{{ ($modelShow->order_type === null) ? 'undefined': $modelOrder->orderTypes($modelShow->order_type) }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Availability Status')</th>
                    <td>{{ ($modelShow->availability_status === null) ? 'undefined' : $modelShow->availablityStatus($modelShow->availability_status) }}</td>
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
                    <td>@lang('admincommon.Inactive')</td>
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