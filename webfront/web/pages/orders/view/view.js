define(function(require, exports, module) {
    //requirements
    var Page = require('kit/core/page'),
        SuppliersCollection = require('collections/suppliers'),
        OrderModel = require('models/order'),
        OrderProductsCollection = require('collections/orderProducts'),
        Form_order = require('blocks/form/form_order/form_order');

    require('jquery');

    return Page.extend({
        __name__: module.id,
        partials: {
            '#content': require('tpl!./content.html')
        },
        orderId: null,
        permissions: function() {
            return !LH.isAllow('stores/{store}/orders', 'GET');
        },
        initialize: function() {
            var page = this;

            page.collections = {
                suppliers: new SuppliersCollection()
            };

            page.models = {
                order: new OrderModel({
                    id: page.orderId
                })
            };

            $.when(page.collections.suppliers.fetch(), page.models.order.fetch()).done(function() {
                page.render();
            });
        },
        render: function() {
            var page = this;

            Page.prototype.render.apply(page, arguments);

            page.blocks = {
                form_order: new Form_order({
                    model: page.models.order
                })
            };

            document.getElementById('form_order__removeLink').addEventListener('click', function(e) {
                e.preventDefault();

                if (e.target.classList.contains('preloader_rows')) {
                    return;
                }

                if (confirm('Вы уверены?')) {
                    e.target.classList.add('preloader_rows');
                    page.blocks.form_order.disable();

                    page.models.order.destroy({
                        success: function() {
                            router.navigate('/orders');
                        }
                    });
                }
            });
        }
    });
});