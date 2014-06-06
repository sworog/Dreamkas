define(function(require) {
    //requirements
    var Block = require('kit/block'),
        currentUser = require('models/currentUser.inst'),
        when = require('when'),
        _ = require('lodash');

    require('sortable');
    require('backbone');

    return Block.extend({
        resources: {},
        isAllow: true,
        template: require('rv!pages/template.html'),
        partials: {
            globalNavigation: require('rv!blocks/globalNavigation/globalNavigation.html'),
            localNavigation: ''
        },
        observers: {
            status: function(status) {
                var page = this;

                page.el.setAttribute('status', status);
            }
        },

        init: function() {
            var page = this;

            page.el = document.body;

            page._super();

            page.set({
                status: 'loading',
                currentUser: currentUser.toJSON()
            });

            when(_.result(page, 'isAllow')).then(function(isAllow) {
                if (isAllow) {

                    page._initResources();

                    when(page.fetch()).then(function(data) {

                        var autofocus;

                        page.set(data);

                        if (window.PAGE){
                            window.PAGE.destroy();
                        }

                        while (page.el.hasChildNodes()) {
                            page.el.removeChild(page.el.lastChild);
                        }

                        page.insert(page.el);

                        window.PAGE = page;

                        autofocus = page.el.querySelector('[autofocus]');

                        autofocus && autofocus.focus()

                        Sortable.init();

                        page.set('status', 'loaded');
                    });
                } else {
                    page.set('error', {
                        status: '403'
                    });
                }
            });
        },

        fetch: function(resourceNames) {
            var page = this,
                fetched = _.map(resourceNames || _.keys(page.resources), function(resourceName) {
                    return page.resources[resourceName].fetch();
                });

            return when.all(fetched).then(function() {
                var data = _.transform(page.resources, function(result, resource, key) {
                    result[key] = resource.toJSON();
                });

                page.set && page.set(data);
                page.set && page.set('status', 'loaded');

                return data;
            });
        },

        save: function(resourceName) {
            var page = this;

            return when(page.resources[resourceName].save(page.get(resourceName)), function() {
                page.set && page.set('status', 'loaded');
            });
        },

        _initResources: function() {
            var page = this;

            page.resources = _.transform(page.resources, function(result, ResourceInitializer, key) {
                if (ResourceInitializer.extend){
                    result[key] = new ResourceInitializer();
                } else {
                    result[key] = ResourceInitializer.call(page);
                }
            });
        }
    });
});