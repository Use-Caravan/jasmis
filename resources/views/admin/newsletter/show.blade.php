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
          <h1 class="box-title">@lang('admincrud.Newsletter Management')</h1>
          <div class="top-action pull-right">
            <a href="{!! route('newsletter.edit',['id' => $model->newsletter_key]) !!}" title="Add New" class="btn mb15"><i class="fa fa-pencil-square-o"></i>@lang('admincommon.Update')</a>
          </div>
        </div> <!--box-header-->

        <div class="box-body">
          
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="newsletterTable">              
              <tbody>
                <tr>
                    <th>@lang('admincrud.Newsletter Title')</th>
                    <td>{{$model->newsletter_title}}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Newsletter Content')</th>
                    <td>{!! $model->newsletter_content !!}</td>
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