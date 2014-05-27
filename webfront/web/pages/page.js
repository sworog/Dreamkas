define(function(require) {
    //requirements
    var Page = require('kit/page'),
        router = require('router');

    return Page.extend({
        template: require('rv!./template.html'),
        partials: {
            globalNavigation: require('rv!./globalNavigation.html')
        },
        observers: {
            status: function(status){
                var page = this;

                page.el.setAttribute('status', status);
            }
        }
    });
});