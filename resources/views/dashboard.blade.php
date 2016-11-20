@extends('layouts/master')

@section('content')

    @javascript(compact('pusherKey'))

    <div class="dashboard" id="dashboard">
            <google-calendar calendarId="blue-leaf.co.uk_l7sd9lk6skljfprvub3q5g9qjs@group.calendar.google.com" grid="a1:b3"></google-calendar>

            {{-- <google-calendar calendarId="" grid="c1:d3"></google-calendar>

            <google-calendar calendarId="blue-leaf.co.uk_prupcvqhi5f0kq2ev70gk2jqt4@group.calendar.google.com" grid="e1:f3"></google-calendar> --}}

            <current-time grid="g1:h1" dateformat="ddd DD/MM"></current-time>

            <internet-connection grid="g2:h2"></internet-connection>

            <sonos grid="g3:h3"></sonos>
    </div>

@endsection
