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
          <h1 class="box-title">@lang('admincrud.Report Management')</h1>
          
        </div> <!--box-header-->

        <div class="box-body">
           {{--@php echo"<pre>";print_r($model);exit; @endphp --}}
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="orderTable">              
              <tbody>
                <tr>
                    <th>@lang('admincrud.Order Number')</th>
                    <td>{{ $model->order_number }}</td>
                </tr>  
                <tr>
                    <th>@lang('admincommon.Customer Name')</th>
                    <td>{{ $model->first_name.$model->last_name }}</td>
                </tr>
                <tr>
                    <th>@lang('admincommon.Phone Number')</th>
                    <td>{{ $model->user_phone_number }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Vendor Name')</th>
                    <td>{{ $model->vendor_name }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Branch Name')</th>
                    <td>{{ $model->branch_name }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Order Date Time')</th>
                    <td>{{ $model->order_datetime }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Order Type')</th>
                    <td>{{ ($model->order_type === null ) ? 'undefined' : $model->orderTypes($model->order_type) }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Order Total')</th>
                    <td>{{ ($model->order_total === null) ? '-' : $model->order_total }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Payment Type')</th>
                    <td>{{ ($model->payment_type === null) ? 'undefined' : $model->paymentTypes($model->payment_type) }}</td>    
                </tr>                  
                {{--<tr>
                    <th>@lang('admincrud.Item Name')</th>
                    <td>
                        @foreach($model->items as $key => $itemName)
                            {{ ($itemName->item_name === null) ? '-' : $itemName->item_name }}</br>
                            @foreach($itemName->ingredients as $key => $ingredientName )
                                <ul><li>{{ ($ingredientName->ingredient_name === null) ? '-' : $ingredientName->ingredient_name }}</li></ul>
                            @endforeach
                            
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Ingredient Group Name')</th>
                    <td>{{ ($model->ingredient_group_names === null) ? '-' : $model->ingredient_group_names }}</td>
                </tr> 
                <tr>
                    <th>@lang('admincrud.Ingredient Name')</th>
                    <td>{{ ($model->ingredient_names === null) ? '-' : $model->ingredient_names }}</td>
                </tr> --}}
                <tr>
                    <th>@lang('admincommon.Status')</th>
                    @if($model->status == ITEM_ACTIVE)
                    <td>@lang('admincommon.Active')</td>
                    @elseif($model->status == ITEM_INACTIVE)
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