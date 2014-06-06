define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page/page'),
        SuppliersCollection = require('collections/suppliers'),
        OrderModel = require('models/order'),
        router = require('router'),
        Form_order = require('blocks/form/form_order/form_order');

    require('jquery');

    return Page.extend({
        templates: {
            content: require('tpl!./content.ejs'),
            localNavigation: require('tpl!blocks/localNavigation/localNavigation_orders.ejs'),
            globalNavigation: require('tpl!blocks/globalNavigation/globalNavigation_store.ejs')
        },
        events: {
            'click .form_order__removeLink': function(e){
                var page = this;

                e.preventDefault();

                if (e.target.classList.contains('preloader_rows')) {
                    return;
                }

                if (confirm('Вы уверены?')) {
                    e.target.classList.add('preloader_rows');
                    page.blocks.form_order.disable();

                    page.models.order.destroy({
                        success: function() {
                            router.navigate('/stores/' + page.get('params.storeId') + '/orders');
                        }
                    });
                }
            }
        },
        params: {
            orderId: null,
            storeId: null
        },
        collections: {
            suppliers: function(){
                return new SuppliersCollection();
            }
        },
        models: {
            order: function(){
                var page = this;

                return new OrderModel({
                    storeId: page.get('params.storeId'),
                    id: page.params.orderId
                });
            },
            store: function() {
                var page = this,
                    StoreModel = require('models/store');

                return new StoreModel({
                    id: page.get('params.storeId')
                });
            }
        },
        blocks: {
            form_order: function(){
                var page = this;

                return new Form_order({
                    storeId: page.get('params.storeId'),
                    model: page.models.order,
                    collections: _.pick(page.collections, 'suppliers')
                })
            }
        }
    });
});