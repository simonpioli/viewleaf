<!--
TODO
Set background based on day vs night (Maybe find some animated backgrounds that match the icons)
-->

<template>
    <grid :position="grid" modifiers="padded">
        <div class="weather-wrapper" v-if="typeof(forecast.current) !== 'undefined'">
            <section class="weather-current">
                <h1 class="weather-current__main-header">{{ forecast.current.summary }}</h1>
                <canvas id="current-icon" class="weather-current__main-icon"></canvas>
                <p class="weather-current__detail weather-current__temp"><span class="detail__label">Temp: </span>{{ forecast.current.temperature.toFixed(0) }}ºC</p>
                <p class="weather-current__detail weather-current__temp-feels-like" v-if="forecast.current.temperature != forecast.current.apparentTemperature"><span class="detail__label">Feels Like </span>{{ forecast.current.apparentTemperature.toFixed(0) }}ºC</p>
                <p class="weather-current__detail weather-current__wind">
                    <span class="detail__label">Wind: </span>
                    {{ forecast.current.windSpeed.toFixed(0) }} MPH
                    <windDirection :bearing="forecast.current.windBearing"></windDirection>
                </p>
                <p class="weather-current__detail weather-current__uv"><span class="detail__label">UV: </span>{{ forecast.current.uvIndex }}</p>
            </section>
            <section class="weather-hourly">
                <ul class="weather-hourly__list">
                    <li class="weather-item" v-for="(item, key) in forecast.hourly">
                        <span class="weather-item__time">{{ item.time }}</span>
                        <canvas class="weather-item__icon" :id="'icon-' +  key"></canvas>
                        <span class="weather-item__summary" v-html="item.summary"></span>
                        <span class="weather-item__temps">
                            <span class="detail__label">T: </span>
                            {{ item.temperature.toFixed(0) }}ºC&nbsp;
                            <span class="weather-item__temps__fl" v-if="item.temperature != item.apparentTemperature">F/L {{ item.apparentTemperature.toFixed(0) }}ºC</span>
                        </span>
                        <span class="weather-item__wind">
                            <span class="detail__label">W: </span>
                            {{ item.windSpeed.toFixed(0) }} MPH
                            <windDirection :bearing="item.windBearing"></windDirection>
                        </span>
                    </li>
                </ul>
            </section>
             <p class="weather-updated">Last updated: {{ retrievedFormatted }}</p>
        </div>
        <div class="weather-wrapper" v-else>
            <h1 class="weather-empty-header">No weather information available for {{ city }}</h1>
        </div>
    </grid>
</template>

<script>
import Echo from '../mixins/echo';

import Grid from './Grid';
import WindDirection from './weatherforecast/WindDirection';
import { addClassModifiers } from '../helpers';
import SaveState from '../mixins/save-state';
var Skycons = require('../skycons');
import moment from 'moment-timezone';

export default {

    components: {
        Grid,
        WindDirection
    },

    mixins: [Echo, SaveState],

    props: ['grid', 'city'],

    computed: {

    },

    data() {
        return {
            forecast: [],
            retrieved: 'Never',
            retrievedFormatted: 'Never'
        };
    },

    updated() {
        this.refreshIcons();
    },

    mounted() {
        this.refreshIcons();
        this.refreshTime();
        setInterval(this.refreshTime, 1000);
    },

    methods: {
        addClassModifiers,

        refreshTime() {
            if (this.retrieved !== 'Never') {
                this.retrievedFormatted = moment(this.retrieved).tz('Europe/London').fromNow();
            }
        },

        refreshIcons() {
            if (typeof(global.skycons) !== 'undefined') {
                global.skycons.remove("current-icon");
                _.forEach(this.forecast.hourly, function(item, key) {
                    global.skycons.remove("icon-" + key, item.icon);
                });
                delete global.skycons;
            }
            global.skycons = new Skycons.Skycons({"color": "white"});
            global.skycons.add("current-icon", this.forecast.current.icon);

            _.forEach(this.forecast.hourly, function(item, key) {
                global.skycons.add("icon-" + key, item.icon);
            });

            global.skycons.play();
        },

        getEventHandlers() {
            return {
                'WeatherForecast.ForecastFetched': response => {
                    var forecast;
                    var targetCity = this.city;
                    _.forEach(response.forecast, function(item, id) {
                        if (id == targetCity) {
                            forecast = item;
                            return;
                        }
                    });

                    this.forecast = forecast;
                    this.retrieved = moment();
                },
            };
        },

        getSavedStateId() {
            return 'weather-forecast-update';
        },
    },
};
</script>
