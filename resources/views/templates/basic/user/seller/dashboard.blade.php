@extends('templates.basic.layouts.wiserlance.dashboard-layout-job')
@section('content')
    @include('templates.basic.user.seller.ajax_dashboard', ['bpcount'])
@endsection
