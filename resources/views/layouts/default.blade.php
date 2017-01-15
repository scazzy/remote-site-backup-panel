@extends('layouts.base')
@section('body')
<div class='app-body'>
@include('partials.header')
@yield('mainContent')
@include('partials.footer')
</div>
@endsection
