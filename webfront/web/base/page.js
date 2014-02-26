define(function(require) {
    //requirements
    var Page = require('kit/core/page'),
        globalNavigation = require('tpl!blocks/globalNavigation/globalNavigation.html'),
        currentUserModel = require('models/currentUser');

    return Page.extend({
        partials: {
            '#globalNavigation': function() {
                return globalNavigation({currentUserModel: currentUserModel});
            },
            '#localNavigation': null,
            '#content': null
        }
    });
});