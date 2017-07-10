<template>
    <grid :position="grid" modifiers="padded">
        <div class="weather-wrapper" v-if="typeof(forecast.current) !== 'undefined'">
            <section class="weather-current">
               <h1 class="weather-current__main-header">{{ forecast.current.summary }}</h1>
               <canvas id="current-icon" class="weather-current__main-icon"></canvas>
               <p class="weather-current__detail weather-current__temp">{{ forecast.current.temperature }}ºC</p>
               <p class="weather-current__detail weather-current__temp-feels-like" v-if="forecast.current.temperature != forecast.current.apparentTemperature"><span class="detail__label">Feels Like </span>{{ forecast.current.apparentTemperature }}ºC</p>
               <p class="weather-current__detail weather-current__wind"><span class="detail__label">Wind: </span>{{ forecast.current.windSpeed }} MPH</p>
               <div class="weather-current__detail weather-current__uv"><span class="detail__label">UV: </span>{{ forecast.current.uvIndex }}</div>
            </section>
            <section class="weather-hourly">
                <ul class="weather-hourly__list">
                    <li class="weather-item" v-for="(item, key) in forecast.hourly">
                        <span class="weather-item__time">{{ item.time }}</span>
                        <canvas class="weather-item__icon" :id="'icon-' +  key"></canvas>
                        <span class="weather-item__summary">{{ item.summary }}</span>
                        <span class="weather-item__temps">{{ item.temperature }}ºC <span class="weather-item__temps__fl" v-if="item.temperature != item.apparentTemperature">F/L {{ item.apparentTemperature }}ºC</span></span>
                    </li>
                </ul>
            </section>
        </div>
        <div class="weather-wrapper" v-else>
            <h1 class="weather-empty-header">No weather information available for {{ city }}</h1>
        </div>
    </grid>
</template>

<script>
import Echo from '../mixins/echo';

import Grid from './Grid';
import { addClassModifiers } from '../helpers';
import SaveState from '../mixins/save-state';
var Skycons = require('../skycons');

export default {

    components: {
        Grid,
    },

    mixins: [Echo, SaveState],

    props: ['grid', 'city'],

    computed: {

    },

    data() {
        return {
            forecast: []
        };
    },

    updated() {
        this.refreshIcons();
    },

    mounted() {
        this.refreshIcons();
    },

    methods: {
        addClassModifiers,

        refreshIcons() {
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
                },
            };
        },

        getSavedStateId() {
            return 'weather-forecast-update';
        },
    },
};
</script>
