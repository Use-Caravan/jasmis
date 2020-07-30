@extends('admin.layouts.layout')
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
        @include('admin.layouts.partials._flashmsg')
        <h1 class="box-title">@lang('admincrud.Delivery Boy Management')</h1>
            <div class="top-action">
                <a href="{!! route('deliveryboy.create') !!}" title="Add New" class="btn mb15"><i class="fa fa-plus-circle"></i>@lang('admincommon.Add New')</a>
                <a href="{!! route('deliveryboy.tracking') !!}" title="Add New" class="btn mb15"><i class="fa fa-plus-circle"></i>@lang('admincrud.Track Delivery boy')</a>
            </div>

        </div> <!--box-header-->

        <div class="box-body">
            <div class="table-responsive">
            <table class="table table-bordered table-striped" id="deliveryboyTable">
              <thead>
              <tr>
                  <th width="20">@lang('admincommon.S.No')</th>                  
                  <th>@lang('admincommon.User Name')</th>
                  <th>@lang('admincommon.Email')</th>
                  <th>@lang('admincommon.Mobile Number')</th>
                  <th>@lang('admincrud.City Name')</th>                                              
                  <th>@lang('admincrud.Online Status')</th>
                  <th class="status">@lang('admincommon.Status')</th>
                  <th class="action">@lang('admincommon.Action')</th>
              </tr>
                @foreach($drivers as $key => $value)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $value['name'] }}</td>
                        <td>{{ $value['email'] }}</td>
                        <td>{{ $value['phone_number'] }}</td>                        
                        <td>{{ $value['city'] }}</td>                                           
                        <td>
                            {{ $deliveryboy->onlineStatus($value['status']) }}
                        </td>
                        <td>
                            <label class="switch" for="id_{{ $value['_id'] }}">
                                <input type="checkbox" itemkey="{{ $value['_id'] }}" class="deliveryboy_status" id="id_{{ $value['_id'] }}" @if( $value['status'] === ITEM_ACTIVE || $value['status'] === DRIVER_ACTIVE || $value['status'] === DRIVER_ONLINE) checked="true" @endif >
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td class="action">                            
                            <a href="{{ route('deliveryboy.edit',['deliveryboy' => $value['_id'] ]) }}" title="Edit">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a href="javascript:" class="trash" title="Are you sure?" data-toggle="popover" data-placement="left" data-target="#delete_confirm" data-original-title="Are you sure?">
                                <i class="fa fa-trash"></i>
                            </a>
                            <form action="{{ route('deliveryboy.destroy',$value['_id']) }}" id="deleteForm{{ $value['_id']  }}" method="post">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @endforeach
              </thead>
            </table>
          </div>
        </div> <!--box-body-->
    </div> <!--box-->
  </section>
</div> <!--content-wrapper-->
<script>
$(document).ready(function()
{    
    $('body').on('change','.deliveryboy_status',function (e, data) {
        var itemkey = $(this).attr('itemkey');        
        var action = "{{ route('deliveryboy.status') }}"
        var status = ($(this).prop('checked') == true) ?  1  : 4 ;        
        $.ajax({
            url: action,
            type: 'post',
            data : { itemkey : itemkey,status : status },
            success: function(result) {
                if(result.status == AJAX_SUCCESS ){
                    successNotify(result.msg);
                }else{
                    errorNotify(result.msg);
                }
            }
        }); 
    });
});
</script>
@endsection

