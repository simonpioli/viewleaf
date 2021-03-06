import { upperFirst } from 'lodash';
import moment from 'moment';

export function formatNumber(value) {
    if (!value) {
        return 0;
    }

    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
}

export function addClassModifiers(base, modifiers = []) {
    if (!Array.isArray(modifiers)) {
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

    const diffInDays = date.diff(moment(), 'days');
    if (diffInDays > 15 || diffInDays < -15) {
        return date.format('DD/MM/YYYY HH:mm');
    }

    return date.fromNow();
}

export function timeFormat(startString, endString) {
    const start = moment(startString);
    let end = null;
    if (typeof(endString) !== 'undefined') {
        end = moment(endString);
    }
    const today = moment().endOf('day');

    if (start.isAfter(today)) {
        return start.calendar();
    }
    let str = start.format('HH:mm');
    if (end) {
        str = start.format('HH:mm') + ' - ' + end.format('HH:mm')
    }
    return str;
}

export function launchIntoFullscreen(element) {
    if (element.requestFullscreen) {
        element.requestFullscreen();
    } else if (element.mozRequestFullScreen) {
        element.mozRequestFullScreen();
    } else if (element.webkitRequestFullscreen) {
        element.webkitRequestFullscreen();
    } else if (element.msRequestFullscreen) {
        element.msRequestFullscreen();
    }
}

export function exitFullscreen() {
    if (document.exitFullscreen) {
        document.exitFullscreen();
    } else if (document.mozCancelFullScreen) {
        document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) {
        document.webkitExitFullscreen();
    }
}

export function burnGuard() {
    var $burnGuard = $('<div>').attr('id', 'burnGuard').css({
        'background-color': '#FF00FF',
        'width': '1px',
        'height': $(document).height() + 'px',
        'position': 'absolute',
        'top': '0px',
        'left': '0px',
        'display': 'none',
        'z-index': '9000'
    }).appendTo('body');

    var colors = ['#FF0000', '#00FF00', '#0000FF'],
        color = 0,
        delay = 1800000,
        scrollDelay = 1000;

    function burnGuardAnimate() {
        color = ++color % 3;
        var rColor = colors[color];
        $burnGuard.css({
            'left': '0px',
            'background-color': rColor,
        }).show().animate({
            'left': $(window).width() + 'px'
        }, scrollDelay, function() {
            $(this).hide();
        });
        setTimeout(burnGuardAnimate, delay);
    }
    setTimeout(burnGuardAnimate, delay);
}
