define(function(require) {
    //requirements
    var Page = require('kit/page'),
        router = require('router');

    return Page.extend({
        params: {
            edit: null
        },
        partials: {
            content: require('tpl!./content.ejs'),
            localNavigation: require('tpl!blocks/localNavigation/localNavigation_catalog.ejs')
        },
        collections: {
            groups: require('collections/groups')
        }
    });
});