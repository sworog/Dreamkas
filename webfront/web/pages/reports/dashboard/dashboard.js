define(function(require, exports, module) {
    //requirements
    var Page = require('kit/core/page'),
        StoreGrossSalesByHourModel = require('models/storeGrossSalesByHours'),
        GrossSalesByStoresCollection = require('collections/grossSalesByStores'),
        GrossSalesByGroupsCollection = require('collections/catalogGroups'),
        currentUserModel = require('models/currentUser'),

        Table_grossSalesByStores = require('blocks/table/table_grossSalesByStores/table_grossSalesByStores');

    require('jquery');

    return Page.extend({
        __name__: module.id,
        partials: {
            '#content': require('tpl!./content.html')
        },
        permissions: function(){
            return !LH.isReportsAllow();
        },
        models: {
            storeGrossSalesByHours: function(){
                var storeGrossSalesByHoursModel = null;

                if (LH.isReportsAllow(['storeGrossSalesByHours'])){
                    storeGrossSalesByHoursModel = new StoreGrossSalesByHourModel();
                    storeGrossSalesByHoursModel.storeId = currentUserModel.stores.at(0).id
                }

                return storeGrossSalesByHoursModel;
            }
        },
        collections: {
            grossSalesByStores: function(){
                var grossSalesByStores = null;

                if (LH.isReportsAllow(['grossSalesByStores'])){
                    grossSalesByStores = new GrossSalesByStoresCollection();
                }

                return grossSalesByStores;
            },
            grossSalesByGroups: function(){
                var grossSalesByGroups = null;

                if (LH.isReportsAllow(['grossSalesByGroups'])){
                    grossSalesByGroups = new GrossSalesByGroupsCollection([], {
                        storeId: currentUserModel.stores.at(0).id
                    });
                }

                return grossSalesByGroups;
            }
        },
        blocks: {
            table_grossSalesByStores: function(){
                var page = this;

                return new Table_grossSalesByStores({
                    el: document.getElementById('table_grossSalesByStores'),
                    collections: _.pick(page.collections, 'grossSalesByStores')
                });
            }
        },
        initialize: function(){
            var page = this,
                fetchData = [];

            page.models = {
                storeGrossSalesByHours: page.models.storeGrossSalesByHours(),
                store: currentUserModel.stores.length ? currentUserModel.stores.at(0) : null
            };

            page.collections = {
                grossSalesByStores: page.collections.grossSalesByStores(),
                grossSalesByGroups: page.collections.grossSalesByGroups()
            };

            if (page.models.storeGrossSalesByHours){
                fetchData.push(page.models.storeGrossSalesByHours.fetch());
            }

            if (page.collections.grossSalesByStores){
                fetchData.push(page.collections.grossSalesByStores.fetch());
            }

            if (page.collections.grossSalesByGroups){
                fetchData.push(page.collections.grossSalesByGroups.fetch());
            }

            $.when.apply($, fetchData).done(function(){
                page.render();

                page.blocks = {
                    table_grossSalesByStores: page.blocks.table_grossSalesByStores.call(page)
                }
            });
        }
    });
});