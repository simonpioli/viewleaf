<template>
    <grid :position="grid" modifiers="transparent">
        <section :class="addClassModifiers('sonos', currentlyPlaying ? 'playing' : 'stopped')">
            <div class="sonos__content" v-if="currentlyPlaying">
                <div class="sonos__cover" v-if="hasCover" v-bind:style="{ backgroundImage: 'url(' + artwork + ')' }">
                </div>
                <div class="sonos__text">
                    <div class="sonos__artist">
                        {{ artist }}
                    </div>
                    <div class="sonos__track">
                        {{ trackName }}
                    </div>
                    <span class="sonos__user">
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
            artist: '',
            trackName: '',
            artwork: '',
            duration: '',
            position: ''
        };
    },

    computed: {
        currentlyPlaying() {
            return !! this.artist;
        },
        hasCover() {
            return !! this.artwork;
        },
    },

    methods: {
        addClassModifiers,

        getEventHandlers() {
            return {
                'Sonos.NothingPlaying': () => {
                    this.artist = '';
                    this.trackName = '';
                    this.artwork = '';
                    this.duration = '';
                    this.position = '';
                },
                'Sonos.TrackIsPlaying': response => {
                    this.artist = response.trackInfo.artist;
                    this.trackName = response.trackInfo.title;
                    this.artwork = response.trackInfo.albumArt;
                    this.duration = response.trackInfo.duration;
                    this.position = response.trackInfo.position;
                },
            };
        },

        pollForTime() {
            // poke the new endpoint for firing the checker every 5 seconds
        },

        getSavedStateId() {
            return 'sonos';
        },
    },
};
</script>
