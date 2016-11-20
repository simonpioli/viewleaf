import { upperFirst } from 'lodash';
import moment from 'moment';

export function formatNumber(value) {
    if (! value) {
        return 0;
    }

    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
}

export function addClassModifiers(base, modifiers = []) {
    if (! Array.isArray(modifiers)) {
        modifiers = modifiers.split(' ');
    }

    modifiers = modifiers.map(modifier => `${base}--${modifier}`);

    return [base, ...modifiers];
}

export function relativeDate(value) {
    const date = moment(value);

    const diffInDays = date.diff(moment(), 'days');

    if (diffInDays < 7) {
        return date.calendar();
    }

    return upperFirst(date.fromNow());
}

export function relativeTime(value) {
    const date = moment(value);

    const diffInMins = date.diff(moment(), 'minutes');

    if (diffInMins > 59) {
        return date.format('HH:mm');
    }

    return upperFirst(date.fromNow());
}

export function timeFormat(startString, endString) {
    const start = moment(startString);
    const end = moment(endString);
    const today = moment().endOf('day');

    if (start.isAfter(today)) {
        return start.fromNow();
    }
    return start.format('HH:mm') + ' - ' + end.format('HH:mm');
}

// export function nowNext(events, event) {
//     console.log(events);
//     const now = moment();
//     var evStart = moment(event.start);
//     var evEnd = moment(event.end);



//     return false;
// }