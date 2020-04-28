@extends('admin.layouts.layout')
@section('content')
  <div class="content-wrapper">
    <section class="content">
        <div class="row dashboard">
            <div class="col-xs-3">
                <div class="box blue">
                    <i class="fa fa-paper-plane"></i>
                    <h5>{{ __('admincrud.Total Orders') }}</h5>
                    <span>{{$order_report['total_orders']}}</span>
                </div>
            </div> <!--col-xs-3-->
            <div class="col-xs-3">
                <div class="box yellow">
                    <i class="fa fa-paper-plane"></i>
                    <h5>{{ __('admincrud.Pending Orders') }}</h5>
                    <span>{{$order_report['pending_orders']}}</span>
                </div>
            </div> <!--col-xs-3-->
            <div class="col-xs-3">
                <div class="box red">
                    <i class="fa fa-paper-plane"></i>
                    <h5>{{ __('admincrud.Rejected Orders') }}</h5>
                    <span>{{$order_report['rejected_orders']}}</span>
                </div>
            </div> <!--col-xs-3-->
            <div class="col-xs-3">
                <div class="box green">
                    <i class="fa fa-paper-plane"></i>
                    <h5>{{ __('admincrud.Delivered Orders') }}</h5>
                    <span>{{$order_report['delivered_orders']}}</span>
                </div>
            </div> <!--col-xs-3-->
            <div class="col-xs-3">
                <div class="box green">
                    <i class="fa fa-paper-plane"></i>
                    <h5>{{ __('admincrud.Total Turnover') }}</h5>
                    <span>{{ Common::currency($order_report['total_turnover']) }}</span>
                </div>
            </div> <!--col-xs-3-->   
            @if(APP_GUARD === GUARD_ADMIN)          
            <div class="col-xs-3">
                <div class="box red">
                    <i class="fa fa-paper-plane"></i>
                    <h5>{{ __('admincrud.Total Customers') }}</h5>
                    <span>{{$order_report['user_count']}}</span>
                </div>
            </div> <!--col-xs-3-->
            @endif
            @if(APP_GUARD !== GUARD_OUTLET) 
            <div class="col-xs-3">
                <div class="box yellow">
                    <i class="fa fa-paper-plane"></i>
                    <h5>{{ __('admincrud.Total Outlets') }}</h5>
                    <span>{{$order_report['branch_count']}}</span>
                </div>
            </div> <!--col-xs-3-->
            @endif
            @if(APP_GUARD === GUARD_ADMIN) 
            <div class="col-xs-3">
                <div class="box blue">
                    <i class="fa fa-paper-plane"></i>
                    <h5>{{ __('admincrud.Total Drivers') }}</h5>
                    <span>{{$order_report['driver_count']}}</span>
                </div>
            </div> <!--col-xs-3-->            
            @endif
        </div>

        <div class="clear"></div>

        <div class="row new_data">
            <div class="col-sm-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h1 class="box-title">{{ __('admincrud.Today Sales') }} - <span> {{ date('l, F d, Y') }} </span></h1>
                    </div> <!--box-header-->
                
                    <div class="box-body">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <td>{{ __('admincrud.Total Orders') }}</td>
                                    <td>{{$order_report['today']['order_count']}}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('admincrud.Total Sales') }}</td>
                                    <td>{{ Common::currency($order_report['today']['order_total']) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('admincrud.Pending Orders') }}</td>
                                    <td>{{$order_report['today']['pending_order_count']}}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('admincrud.Delivered Orders') }}</td>
                                    <td>{{$order_report['today']['delivered_order_count']}}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('admincrud.Rejected Orders') }}</td>
                                    <td>{{$order_report['today']['rejected_order_count']}}</td>
                                </tr>
                            </tbody>
                        </table>            
                    </div> <!--box-body-->
                </div> <!--box-->
            </div> <!--col-sm-6-->

            <div class="col-sm-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h1 class="box-title">{{ __('admincrud.This Month') }} - <span>{{ date('F Y') }}</span></h1>              
                    </div> <!--box-header-->

                    <div class="box-body">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <td>{{ __('admincrud.Total Orders') }}</td>
                                    <td>{{$order_report['month']['order_count']}}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('admincrud.Total Sales') }}</td>
                                    <td>{{ Common::currency($order_report['month']['order_total']) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('admincrud.Pending Orders') }}</td>
                                    <td>{{$order_report['month']['pending_order_count']}}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('admincrud.Delivered Orders') }}</td>
                                    <td>{{$order_report['month']['delivered_order_count']}}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('admincrud.Rejected Orders') }}</td>
                                    <td>{{$order_report['month']['rejected_order_count']}}</td>
                                </tr>
                            </tbody>
                        </table>            
                    </div> <!--box-body-->
                </div> <!--box-->
            </div> <!--col-sm-6-->

            <div class="col-sm-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h1 class="box-title">{{ __('admincrud.This Year') }} - <span>{{ date('Y') }}</span></h1>              
                    </div> <!--box-header-->

                    <div class="box-body">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <td>{{ __('admincrud.Total Orders') }}</td>
                                    <td>{{$order_report['year']['order_count']}}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('admincrud.Total Sales') }}</td>
                                    <td>{{ Common::currency($order_report['year']['order_total']) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('admincrud.Pending Orders') }}</td>
                                    <td>{{$order_report['year']['pending_order_count']}}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('admincrud.Delivered Orders') }}</td>
                                    <td>{{$order_report['year']['delivered_order_count']}}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('admincrud.Rejected Orders') }}</td>
                                    <td>{{$order_report['year']['rejected_order_count']}}</td>
                                </tr>
                            </tbody>
                        </table>            
                    </div> <!--box-body-->
                </div> <!--box-->
            </div> <!--col-sm-6-->

            <div class="col-sm-6">
                <div class="box">
                    <div class="box-header with-border">
                        @php 
                        $current_month = date('m');
                        $current_year = date('Y');
                        if($current_month>=1 && $current_month<=3)
                        {
                            $start_date = strtotime('1-January-'.$current_year);  // timestamp or 1-Januray 12:00:00 AM
                            $end_date = strtotime('1-April-'.$current_year);  // timestamp or 1-April 12:00:00 AM means end of 31 March
                        }
                        else  if($current_month>=4 && $current_month<=6)
                        {
                            $start_date = strtotime('1-April-'.$current_year);  // timestamp or 1-April 12:00:00 AM
                            $end_date = strtotime('1-July-'.$current_year);  // timestamp or 1-July 12:00:00 AM means end of 30 June
                        }
                        else  if($current_month>=7 && $current_month<=9)
                        {
                            $start_date = strtotime('1-July-'.$current_year);  // timestamp or 1-July 12:00:00 AM
                            $end_date = strtotime('1-October-'.$current_year);  // timestamp or 1-October 12:00:00 AM means end of 30 September
                        }
                        else  if($current_month>=10 && $current_month<=12)
                        {
                            $start_date = strtotime('1-October-'.$current_year);  // timestamp or 1-October 12:00:00 AM
                            $end_date = strtotime('1-January-'.($current_year+1));  // timestamp or 1-January Next year 12:00:00 AM means end of 31 December this year
                        }
                        @endphp
                        <h1 class="box-title">{{ __('admincrud.This Quarter Year') }} - <span> {{ date('F d, Y',$start_date)." - ".date('F d, Y',$end_date)  }}</span></h1>              
                    </div> <!--box-header-->

                    <div class="box-body">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <td>{{ __('admincrud.Total Orders') }}</td>
                                    <td>{{$order_report['quater_year']['order_count']}}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('admincrud.Total Sales') }}</td>
                                    <td>{{ Common::currency($order_report['quater_year']['order_total']) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('admincrud.Pending Orders') }}</td>
                                    <td>{{$order_report['quater_year']['pending_order_count']}}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('admincrud.Delivered Orders') }}</td>
                                    <td>{{$order_report['quater_year']['delivered_order_count']}}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('admincrud.Rejected Orders') }}</td>
                                    <td>{{$order_report['quater_year']['rejected_order_count']}}</td>
                                </tr>
                            </tbody>
                        </table>            
                    </div> <!--box-body-->
                </div> <!--box-->
            </div> <!--col-sm-6-->
        </div>
    </section>
  </div> <!--content-wrapper-->

</div>
  
@endsection
      <!-- /.row -->