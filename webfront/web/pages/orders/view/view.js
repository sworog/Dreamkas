define(function(require, exports, module) {
    //requirements
    var Page = require('page'),
        SuppliersCollection = require('collections/suppliers'),
        OrderModel = require('models/order'),
        Form_order = require('blocks/form/form_order/form_order');

    require('jquery');

    return Page.extend({
        templates: {
            content: require('tpl!./content.html'),
            localNavigation: require('tpl!../localNavigation.html')
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
                            router.navigate('/orders');
                        }
                    });
                }
            }
        },
        params: {
            orderId: null
        },
        isAllow: function() {
            return LH.isAllow('stores/{store}/orders', 'GET');
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
                    id: page.params.orderId
                });
            }
        },
        blocks: {
            form_order: function(){
                var page = this;

                return new Form_order({
                    model: page.models.order
                })
            }
        }
    });
});