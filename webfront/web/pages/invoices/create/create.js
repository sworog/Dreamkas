define(function(require, exports, module) {
    //requirements
    var Page = require('page'),
        currentUserModel = require('models/currentUser');

    return Page.extend({
        templates: {
            content: require('tpl!./content.html'),
            localNavigation: require('tpl!../localNavigation.html')
        },
        localNavigationActiveLink: 'create',
        params: {
            storeId: null
        },
        isAllow: function() {
            var page = this;

            return currentUserModel.stores.length && currentUserModel.stores.at(0).id === page.params.storeId;
        }
    });
});