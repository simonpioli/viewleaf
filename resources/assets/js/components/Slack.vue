<template>
    <grid :position="grid" modifiers="padded white overflow">
        <section v-if="from != 'No-one'" class="slack">
            <h2 class="slack__header">Latest Announcement</h2>
            <p class="slack__message js-marqueeMessage">
                {{ from }} said: <strong>{{ message }}</strong> {{ relativeTime(posted) }}
            </p>
        </section>
        <section class="slack slack--offline" v-else>
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
            from: 'No-one',
            posted: '',

            default: {
                message: '',
                from: 'No-one',
                posted: '',
            }
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
                        if (moment(response.posted).isAfter(moment().subtract(7, 'days'))) {
                            this.from = response.from;
                            this.message = response.message
                                .replace('<!channel>', '')
                                .replace('<!channel|@channel>', '')
                                .replace('<!here>', '')
                                .replace('<!here|@here>', '')
                                .replace('@bigscreen', '');
                            this.posted = moment(response.posted);
                        } else {
                            this.from = this.default.from;
                            this.message = this.default.message;
                            this.posted = this.default.posted;
                            $('.js-marqueeMessage').marquee('destroy');
                        }
                    }
                }
            }
        },

        getSavedStateId() {
            return 'slack';
        }
    },

    mounted: function() {
        if (this.from != 'No-one') {
            $('.js-marqueeMessage').marquee({
                duration: 30000
            });
        }
    },

    updated: function() {
        setTimeout(function(){
            $('.js-marqueeMessage').marquee('destroy');
            $('.js-marqueeMessage').marquee({
                duration: 30000
            });
        }, 500);
    }

}
</script>