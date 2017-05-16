<template>
   <span v-html="formattedMessage">{{ formattedMessage }}</span>
</template>
<script>

import Avatar from './Avatar';
import Emoji from './Emoji';
import { addClassModifiers, relativeDate, relativeTime } from '../../helpers';
import moment from 'moment';
export default {
    components: {
        Avatar,
        Emoji
    },
    props: ['message', 'mentions', 'emoji', 'from'],

    computed: {
        formattedMessage: function() {
            message = this.from.real_name + ' said: “' + this.message + '” ' + relativeTime(this.posted) + '.';
            // Loop array of mentions and replace the ID crap with the first name
            _.forEach(this.mentions, function(person) {
                // avatar = createElement(Avatar, person); // Need to work out how to actually do this...
                avatar = '<img src="' + person.thumbnail + '">';
                message = message.replace('<@' + person.id + '>', avatar + ' ' + person.first_name);
            });

            _.forEach(this.emoji, function(item) {
                emojiElem = createElement(Emoji, item);
                message = message.replace(':' + item.label + ':', emojiElem);
            });

            console.log(message);

            return message;
        }
    },

    methods: {
        addClassModifiers,
        relativeDate,
        relativeTime
    }
}
</script>