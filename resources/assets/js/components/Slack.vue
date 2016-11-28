<template>
    <grid :position="grid" modifiers="padded white overflow">
        <section v-if="message !== ''" class="slack">
            <h2 class="slack__header">Latest Announcement</h2>
            <p class="slack__message js-marqueeMessage">
                {{ from }} said: <strong>{{ message }}</strong> {{ relativeTime(posted) }}
            </p>
        </section>
        <section v-else class="slack slack--offline">
            <h2 class="slack__header">Latest Announcement Unavailable</h2>
            <p class="slack__message">I couldn't find a relevant message to show.</p>
        </section>
    </grid>
</template>
<script>
import Echo from '../mixins/echo';
import Grid from './Grid';
import { addClassModifiers, relativeDate, relativeTime } from '../helpers';
import SaveState from '../mixins/save-state';
import moment from 'moment';

export default {

    components: {
        Grid,
    },

    mixins: [Echo, SaveState],

    props: ['grid'],

    data() {
        return {
            message: '',
            from: '',
            posted: ''
        }
    },

    methods: {
        addClassModifiers,
        relativeDate,
        relativeTime,

        getEventHandlers() {
            return {
                'Slack.Announcement': response => {
                    if (response.message !== this.message) {
                        this.from = response.from;
                        this.message = response.message
                            .replace('<!channel>', '')
                            .replace('<!channel|@channel>', '')
                            .replace('<!here>', '')
                            .replace('<!here|@here>', '')
                            .replace('@bigscreen', '');
                        this.posted = moment(response.posted);
                    }
                },

                'Slack.NoAnnouncement': response => {
                    this.from = '';
                    this.message = '';
                    this.posted = '';
                }
            }
        },

        getSavedStateId() {
            return 'slack';
        }
    },

    mounted: function() {
        if (this.message != '') {
            $('.js-marqueeMessage').marquee({
                duration: 30000
            });
        }
    },

    beforeUpdate: function() {
        $('.js-marqueeMessage').marquee('destroy');
        console.log('Destroyed');
    },

    updated: function() {
        var _this = this;
        setTimeout(function(){
            if (_this.message != '') {
                $('.js-marqueeMessage').marquee({
                    duration: 30000
                });
            }
        }, 500);
    }

}
</script>