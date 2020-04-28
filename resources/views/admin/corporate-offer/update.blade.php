@extends('admin.layouts.layout')
@section('content')

@php
  $url = route('corporate-offer.update', ['id' => $model->corporate_offer_key]);
@endphp
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-info">
          <div class="box-header with-border">
          @include('admin.layouts.partials._flashmsg')
            <h3 class="box-title">@lang('admincommon.Update')</h3>
          </div>
          @include('admin.corporate-offer._form')
        </div>
      </div>
    </div>
  </section>
</div>

@endsection()