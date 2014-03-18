define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form'),
        OrderProductModel = require('models/orderProduct'),
        OrderProductsCollection = require('collections/orderProducts'),
        Autocomplete = require('blocks/autocomplete/autocomplete'),
        cookies = require('cookies');

    require('jquery');
    require('lodash');

    return Form.extend({
        __name__: module.id,
        el: '.form_orderProduct',
        template: require('tpl!blocks/form/form_orderProduct/template.html'),
        model: new OrderProductModel(),
        collection: new OrderProductsCollection(),
        storeProduct: {
            product: {}
        },
        events: {
            'keyup [name="quantity"]': function(e) {
                var block = this;

                block.model.set('quantity', e.target.value.replace(',', '.', 'gi'));
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
                            block.storeProduct = {
                                product: {
                                    id: block.el.querySelector('.autocomplete_storeProduct').value ? 'xxx' : null
                                }
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
        submitSuccess: function() {
            var block = this;

            Form.prototype.submitSuccess.apply(block, arguments);

            block.clear();
            block.render();

            block.el.querySelector('[name="name"]').focus();
        },
        initBlocks: function() {
            var block = this,
                autocomplete_storeProduct = block.el.querySelector('.autocomplete_storeProduct');

            block.blocks = {
                autocomplete: new Autocomplete({
                    el: autocomplete_storeProduct,
                    select: function(event, ui) {
                        block.storeProduct = ui.item.storeProduct;
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
        clear: function() {
            var block = this;

            Form.prototype.clear.apply(block, arguments);

            block.model = new OrderProductModel();

            block.storeProduct = {
                product: {}
            };
        },
        render: function() {
            var block = this;

            $(block.el).find('[name="product"]').val(block.storeProduct.product.id);
            $(block.el).find('.form_orderProduct__retailPrice').html(LH.formatMoney(block.storeProduct.product.purchasePrice));
            $(block.el).find('.form_orderProduct__totalSum').html(LH.formatMoney(_.escape(block.model.get('quantity')) * _.escape(block.storeProduct.product.purchasePrice) || ''));
            $(block.el).find('.form_orderProduct__inventory').html((!block.storeProduct.product.id || block.storeProduct.product.id === 'xxx') ? '&mdash;' : LH.formatAmount(block.storeProduct.inventory));
        }
    });
});