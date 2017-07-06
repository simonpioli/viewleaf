<template>
    <grid :position="grid" modifiers="transparent">
        <section :class="addClassModifiers('sonos', currentlyPlaying ? 'playing' : 'stopped')">
            <div class="sonos__content" v-if="currentlyPlaying">
                <div class="sonos__cover" v-if="hasCover" v-bind:style="{ backgroundImage: 'url(' + artwork + ')' }">
                </div>
                <div class="sonos__text">
                    <div class="sonos__line1">
                        {{ line1 }}
                    </div>
                    <div class="sonos__line2">
                        {{ line2 }}
                    </div>
                    <span class="sonos__time">
                        {{ position }} / {{ duration }}
                    </span>
                </div>
            </div>
            <div class="sonos__empty" v-else></div>
            <div class="sonos__background" v-if="hasCover" v-bind:style="{ backgroundImage: 'url(' + artwork + ')' }"></div>
        </section>
    </grid>
</template>

<script>
import Echo from '../mixins/echo';
import Grid from './Grid';
import { addClassModifiers } from '../helpers';
import SaveState from '../mixins/save-state';

export default {

    components: {
        Grid,
    },

    mixins: [Echo, SaveState],

    props: ['grid'],

    data() {
        return {
            line1: '',
            line2: '',
            artwork: '',
            duration: '',
            position: '',
            polling: false
        };
    },

    computed: {
        currentlyPlaying() {
            return !! this.line1;
        },
        hasCover() {
            return !! this.artwork;
        },
    },

    mounted() {
        this.pollForTime();
    },

    methods: {
        addClassModifiers,

        getEventHandlers() {
            return {
                'Sonos.NothingPlaying': () => {
                    this.line1 = '';
                    this.line2 = '';
                    this.artwork = '';
                    this.duration = '';
                    this.position = '';
                    clearInterval(window.sonosInterval);
                    this.polling = false;
                },
                'Sonos.TrackIsPlaying': response => {
                    this.line1 = response.trackInfo.artist + ' – ' + response.trackInfo.title;
                    if (response.trackInfo.stream !== null) {
                        this.line2 = response.trackInfo.stream;
                    } else {
                        this.line2 = response.trackInfo.album;
                    }
                    this.artwork = response.trackInfo.albumArt;
                    this.duration = response.trackInfo.duration;
                    this.position = response.trackInfo.position;
                    if (!this.polling) {
                        window.sonosInterval = setInterval(this.pollForTime, 5000);
                        this.polling = true;
                    }
                },
            };
        },

        pollForTime() {
            // poke the new endpoint for firing the checker every 5 seconds
            this.$http.get('/api/dashboard/sonos').then(function(response, status, request) {
                return true;
            }, function() {
                console.error('Sonos Request Failed: ' + status);
            });
        },

        getSavedStateId() {
            return 'sonos';
        },
    }
};
</script>
