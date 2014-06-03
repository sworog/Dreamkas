define({
    'for last day': function(day) {
        switch (day) {
            case 1:
                return 'прошлый понедельник';
            case 2:
                return 'прошлый вторник';
            case 3:
                return 'прошлую среду';
            case 4:
                return 'прошлый четверг';
            case 5:
                return 'прошлую пятницу';
            case 6:
                return 'прошлую субботу';
            default:
                return 'прошлое воскресение';
        }
    },
    'last day': function(day) {
        switch (day) {
            case 1:
                return 'прошлый понедельник';
            case 2:
                return 'прошлый вторник';
            case 3:
                return 'прошлая среда';
            case 4:
                return 'прошлый четверг';
            case 5:
                return 'прошлая пятница';
            case 6:
                return 'прошлая суббота';
            default:
                return 'прошлое воскресение';
        }
    }
});