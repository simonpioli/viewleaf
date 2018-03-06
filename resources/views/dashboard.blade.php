@extends('layouts/master')

@section('content')

    @javascript(compact('pusherKey'))

    <div class="dashboard" id="dashboard">

        <a href="#" class="full-screen js-fullScreen">
            <i class="full-screen__icon"></i>
        </a>

        <google-calendar calendar-id="{{-- Redacted Email Address --}}" grid="a1:b3"></google-calendar>

        <google-calendar calendar-id="{{-- Redacted Email Address --}}" grid="c1:d3"></google-calendar>

        <google-calendar calendar-id="{{-- Redacted Email Address --}}" grid="e1:f3"></google-calendar>

        <current-time grid="g1" dateformat="ddd DD/MM"></current-time>

        <internet-connection grid="h1"></internet-connection>

        <weather-forecast grid="g2:h3" city="{{-- Redacted Location --}}"></weather-forecast>

        <sonos grid="g4:h4"></sonos>

        <slack grid="a4:f4"></slack>

    </div>

@endsection
