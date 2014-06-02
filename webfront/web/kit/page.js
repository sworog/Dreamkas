define(function(require) {
    //requirements
    var Block = require('kit/block'),
        currentUser = require('models/currentUser.inst'),
        when = require('when'),
        _ = require('lodash');

    return Block.extend({
        el: document.body,
        resources: {},
        isAllow: true,
        template: require('rv!pages/template.html'),
        partials: {
            globalNavigation: require('rv!pages/globalNavigation.html'),
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

            window.PAGE && window.PAGE.destroy();
            window.PAGE = page;

            page._super();

            page.set({
                status: 'loading',
                currentUser: currentUser.toJSON()
            });

            when(_.result(page, 'isAllow')).then(function(isAllow) {
                if (isAllow) {

                    page._initResources();

                    when(page.fetchAll()).then(function(data) {

                        page.set(data);

                        page.set('status', 'loaded');
                    });
                } else {
                    page.set('error', {
                        status: '403'
                    });
                }
            });
        },

        complete: function() {
            this.el.querySelector('[autofocus]').focus();
        },

        fetch: function(resourceName) {
            var page = this;

            return when(page.resources[resourceName].fetch()).then(function(data) {

                page.set && page.set(resourceName, page.resources[resourceName].toJSON());
                page.set && page.set('status', 'loaded');

                return data;
            });
        },

        fetchAll: function(resourceNames) {
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

            page.resources = _.transform(page.resources, function(result, ResourceClass, key) {
                result[key] = new ResourceClass();
            });
        }
    });
});