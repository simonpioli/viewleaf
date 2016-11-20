<template>
    <grid :position="grid" modifiers="padded white">
        <section v-if="message != ''" class="slack">
            <h2 class="slack__header">Latest Announcement</h2>
            <p class="slack__meta">
                Posted {{ relativeTime(posted) }} by {{ from }}
            </p>
            <p class="slack__message">
                {{ message }}
            </p>
        </section>
        <section class="slack--offline" v-else>
            <h2 class="slack--header">Latest Announcement Unavailable</h2>
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
            message: 'Hello @channel we have LA visiting the office on Weds next week, the new Head of IT Colin Rice and David Williams IT Manager will be here for  most of the day. We need to make an extra good impression as Colin is new to LA and needs to see how amazing we all are. Can everyone who would normally work from home, or is planning to, please work from the office that day so we look nice and full. Thanks in advance :slightly_smiling_face:',
            from: 'Verity Maybury',
            posted: moment('2016-07-13T13:19:00')
        }
    },

    methods: {
        addClassModifiers,
        relativeDate,
        relativeTime,

        getEventHandlers() {
            return {
                'Slack.Announcement': response => {
                    console.log(response);
                    return false;
                }
            }
        },

        getSavedStateId() {
            return 'slack';
        },
    }

}
</script>