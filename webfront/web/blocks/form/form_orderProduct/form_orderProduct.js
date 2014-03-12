define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form'),
        OrderModel = require('models/order'),
        Autocomplete = require('blocks/autocomplete/autocomplete'),
        cookies = require('cookies');

    require('jquery');
    require('lodash');

    return Form.extend({
        __name__: module.id,
        redirectUrl: '/orders',
        el: '.form_orderProduct',
        template: require('tpl!blocks/form/form_orderProduct/template.html'),
        model: new OrderModel(),
        storeProduct: null,
        events: {
            'keyup [name="amount"]': function(e){
                var block = this;

                block.model.set('amount', e.target.value);
                block.render();
            }
        },
        initialize: function(){
            var block = this;

            block.initBlocks();
        },
        initBlocks: function(){
            var block = this,
                autocomplete_storeProduct = block.el.querySelector('.autocomplete_storeProduct');

            block.blocks = {
                autocomplete: new Autocomplete({
                    el: autocomplete_storeProduct,
                    select: function(event, ui) {
                        block.storeProduct = ui.item.storeProduct;
                        block.model.set('product', ui.item.storeProduct.product.id);
                        block.render();
                    },
                    source: function(request, response) {
                        $.ajax({
                            url: LH.baseApiUrl + autocomplete_storeProduct.dataset.url,
                            dataType: "json",
                            headers: {
                                Authorization: 'Bearer ' + cookies.get('token')
                            },
                            data: {
                                query: request.term
                            },
                            success: function(data) {
                                response($.map(data, function(item) {
                                    return {
                                        label: item.product.name,
                                        storeProduct: item
                                    }
                                }));
                            }
                        })
                    }
                })
            }
        },
        render: function(){
            var block = this;

            $(block.el).find('.form_orderProduct__retailPrice').html(LH.formatMoney(block.storeProduct.retailPrice));
            $(block.el).find('.form_orderProduct__totalSum').html(LH.formatMoney(_.escape(block.model.get('amount')) * _.escape(block.storeProduct.retailPrice) || ''));
            $(block.el).find('.form_orderProduct__inventory').html(_.escape(block.storeProduct.inventory));
        }
    });
});