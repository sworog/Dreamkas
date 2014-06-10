define(function(require, exports, module) {
    //requirements
    var Page = require('pages/store');

    return Page.extend({
        partials: {
            content: require('tpl!./content.ejs'),
            localNavigation: require('tpl!blocks/localNavigation/localNavigation_orders.ejs')
        },
        collections: {
            suppliers: require('collections/suppliers')
        },
        models: {
            order: function(){
                var page = this,
                    OrderModel = require('models/order');

                return new OrderModel({
                    storeId: page.get('params.storeId')
                });
            }
        },
        blocks: {
            form_order: function(){
                var page = this,
                    Form_order = require('blocks/form/form_order/form_order');

                return new Form_order({
                    storeId: page.get('params.storeId'),
                    model: page.models.order,
                    collections: _.pick(page.collections, 'suppliers')
                });
            }
        }
    });
});