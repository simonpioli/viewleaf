@extends('layouts/master')

@section('content')

    @javascript(compact('pusherKey'))

    <div class="dashboard" id="dashboard">

        <a href="#" class="full-screen js-fullScreen">
            <i class="full-screen__icon"></i>
        </a>

        {{-- <google-calendar calendar-id="simon.pioli@blue-leaf.co.uk" grid="a1:b3"></google-calendar> --}}

        <google-calendar calendar-id="blue-leaf.co.uk_l7sd9lk6skljfprvub3q5g9qjs@group.calendar.google.com" grid="a1:b3"></google-calendar>

        <google-calendar calendar-id="blue-leaf.co.uk_9ffftu2t7do5dhi2jdn61jpsho@group.calendar.google.com" grid="c1:d3"></google-calendar>

        <google-calendar calendar-id="blue-leaf.co.uk_prupcvqhi5f0kq2ev70gk2jqt4@group.calendar.google.com" grid="e1:f3"></google-calendar>

        <current-time grid="g1" dateformat="ddd DD/MM"></current-time>

        <internet-connection grid="h1"></internet-connection>

        <weather-forecast grid="g2:h3" city="Tattenhall"></weather-forecast>

        <sonos grid="g3:h3"></sonos>

        <slack grid="a4:f4"></slack>

    </div>

@endsection
