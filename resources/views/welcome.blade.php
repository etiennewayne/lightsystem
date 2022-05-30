@extends('layouts.no-navbar')
@section('content')
    @auth
        <navbar-menu prop-is-auth="1"></navbar-menu>
        <welcome prop-is-auth="1"></welcome>
    @else

        <navbar-menu prop-is-auth="0"></navbar-menu>
        <welcome prop-is-auth="0"></welcome>
    @endauth


@endsection

