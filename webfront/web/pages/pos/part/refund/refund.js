define(function(require, exports, module) {
    //requirements
    var PosPart = require('pages/pos/part/part');

    return PosPart.extend({
		title: 'История продаж',
        content: require('ejs!./content.ejs'),
		activeNavigationItem: 'refund'
    });
});