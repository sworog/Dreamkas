define(function(require, exports, module) {
    //requirements
    var Page = require('kit/core/page'),
        GrossSalesByStoresCollection = require('collections/grossSalesByStores'),
        Table_grossSalesByStores = require('blocks/table/table_grossSalesByStores/table_grossSalesByStores');

    require('jquery');

    return Page.extend({
        __name__: module.id,
        partials: {
            '#content': require('tpl!./content.html')
        },
        permissions: function() {
            return !LH.isReportsAllow('grossSalesByStores');
        },
        collections: {
            grossSalesByStores: new GrossSalesByStoresCollection()
        },
        blocks: {
            table_grossSalesByStores: function() {
                var page = this;

                return new Table_grossSalesByStores({
                    el: document.getElementById('table_grossSalesByStores'),
                    collections: _.pick(page.collections, 'grossSalesByStores')
                });
            }
        },
        initialize: function() {
            var page = this;

            page.collections = _.transform(page.collections, function(result, collection, collectionName) {
                result[collectionName] = typeof collection === 'function' ? collection.call(page) : collection
            });

            $.when(page.collections.grossSalesByStores.fetch()).done(function() {

                page.render();

                page.blocks = {
                    table_grossSalesByStores: page.blocks.table_grossSalesByStores.call(page)
                }
            });
        }
    });
});