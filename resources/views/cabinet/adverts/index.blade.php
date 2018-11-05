@extends('layouts.app')

@section('content')
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item"><a class="nav-link" href="{{ route('cabinet.home') }}">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link active" href="{{ route('cabinet.adverts.index') }}">Adverts</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('cabinet.profile.home') }}">Profile</a></li>
    </ul>

    {{--<div class="region-selector" data-selected="{{ json_encode((array)old('regions')) }}"--}}
         {{--data-source="{{ route('ajax.regions') }}">--}}

    {{--</div>--}}
@endsection