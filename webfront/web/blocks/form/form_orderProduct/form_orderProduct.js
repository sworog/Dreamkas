define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form'),
        OrderProductModel = require('models/orderProduct'),
        Autocomplete = require('blocks/autocomplete/autocomplete'),
        cookies = require('cookies');

    require('jquery');
    require('lodash');

    return Form.extend({
        __name__: module.id,
        redirectUrl: '/orders',
        el: '.form_orderProduct',
        template: require('tpl!blocks/form/form_orderProduct/template.html'),
        model: new OrderProductModel(),
        storeProduct: {
            product: {}
        },
        events: {
            'keyup [name="quantity"]': function(e) {
                var block = this;

                block.model.set('quantity', e.target.value);
                block.render();
            },
            'keyup .autocomplete_storeProduct': function(e) {
                var block = this,
                    keyCode = $.ui.keyCode;
                switch (e.keyCode) {
                    case keyCode.PAGE_UP:
                    case keyCode.PAGE_DOWN:
                    case keyCode.UP:
                    case keyCode.DOWN:
                    case keyCode.ENTER:
                    case keyCode.NUMPAD_ENTER:
                    case keyCode.TAB:
                    case keyCode.LEFT:
                    case keyCode.RIGHT:
                    case keyCode.ESCAPE:
                        return;
                        break;
                    default:
                        if (block.storeProduct) {
                            block.model.set('product', null);
                            block.storeProduct = {
                                product: {}
                            };
                            block.render();
                        }
                }
            }
        },
        initialize: function() {
            var block = this;

            block.initBlocks();
            block.render();
        },
        initBlocks: function() {
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
        render: function() {
            var block = this;

            $(block.el).find('.form_orderProduct__retailPrice').html(LH.formatMoney(block.storeProduct.product.purchasePrice));
            $(block.el).find('.form_orderProduct__totalSum').html(LH.formatMoney(_.escape(block.model.get('quantity')) * _.escape(block.storeProduct.product.purchasePrice) || ''));
            $(block.el).find('.form_orderProduct__inventory').html(_.escape(block.storeProduct.inventory) || '&mdash;');
        }
    });
});