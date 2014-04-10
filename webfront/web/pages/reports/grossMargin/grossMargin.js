define(function(require, exports, module) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        GrossMarginCollection = require('collections/grossMargin');

    require('jquery');

    return Page.extend({
        __name__: module.id,
        partials: {
            '#content': require('tpl!./content.html')
        },
        permissions: function() {
            return !LH.isReportsAllow('grossMargin');
        },
        collections: {
            grossMargin: new GrossMarginCollection()
        },
        initialize: function() {
            var page = this;

            page.collections = _.transform(page.collections, function(result, collection, collectionName) {
                result[collectionName] = typeof collection === 'function' ? collection.call(page) : collection
            });

            $.when(page.collections.grossMargin.fetch()).done(function() {

                page.render();

            });
        }
    });
});