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
          <h1 class="box-title">@lang('admincrud.CMS Management')</h1>
        </div> <!--box-header-->

        <div class="box-body">
          
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="enquiryTable">              
              <tbody>
                <tr>
                    <th>@lang('admincrud.Title')</th>
                    <td>{{$modelLang->title}}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Description')</th>
                    <td>{{$modelLang->description}}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Cms Content')</th>
                    <td>{!! $modelLang->cms_content !!}</td>
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