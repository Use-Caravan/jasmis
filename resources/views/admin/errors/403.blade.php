@extends('admin.layouts.layout')
@section('content')
<div class="content-wrapper error_page">
    <section class="content">
        <img src="{{ asset('resources/assets/admin/images/forbidden.png') }}" class="mb25">
        <h2>Access Denied / Forbidden</h2>
        <p>This page or resource you were trying to reach is absolutely forbidden for some reason.</p>
    </section>
</div> <!--content-wrapper-->
@endsection