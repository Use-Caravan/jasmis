@extends('admin.layouts.layout')
@section('content')
<div class="content-wrapper error_page">
    <section class="content">
        <img src="{{ asset('resources/assets/admin/images/404.png') }}" class="mb30">
        <h2>OOPS, SORRY WE CAN'T FIND THAT PAGE!</h2>
        <p>Either something went wrong or the page doesn't exist anymore.</p>
    </section>
</div> <!--content-wrapper-->
@endsection