define(function(require) {
    return {
        main: require('tpl!./calendar.html'),
        header: require('tpl!./header.html'),
        dateList: require('tpl!./dateList.html')
    }
});