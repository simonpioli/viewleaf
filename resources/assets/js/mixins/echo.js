import { forIn } from 'lodash';

export default {
    created() {
        forIn(this.getEventHandlers(), (handler, eventName) => {
            this.$root.echo
                .private(window.db.pusherChannel)
                .listen(`.App.Events.${eventName}`, eventName => handler(eventName));
        });
    },
};
