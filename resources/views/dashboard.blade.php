@extends('layouts/master')

@section('content')
    <div class="dashboard" id="dashboard">

        <a href="#" class="full-screen js-fullScreen">
            <i class="full-screen__icon"></i>
        </a>

        <google-calendar calendar-id="{{-- Redacted Email Address --}}" grid="a1:b3"></google-calendar>

        <google-calendar calendar-id="{{-- Redacted Email Address --}}" grid="c1:d3"></google-calendar>

        <google-calendar calendar-id="{{-- Redacted Email Address --}}" grid="e1:f3"></google-calendar>

        <current-time grid="g1" dateformat="ddd DD/MM"></current-time>

        <internet-connection grid="h1"></internet-connection>

        <sonos grid="g2:h2"></sonos>

        <slack grid="a4:h4"></slack>

    </div>

@endsection

@section('before-js')
    @javascript([
        'pusherKey' => config('broadcasting.connections.pusher.key'),
        'pusherChannel' => env('PUSHER_CHANNEL')
    ])
@endsection
