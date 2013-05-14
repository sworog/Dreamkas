define(
    [
        '/kit/block.js',
        '/models/purchase.js',
        './tpl/tpl.js'
    ],
    function(Block, PurchaseModel, tpl) {
        return Block.extend({
            defaults: {
                tpl: tpl
            },
            initialize: function() {
                var block = this;

                block.render();
            },
            events: {
                'click .saleBox__removeProductLink': function(e) {
                    var block = this,
                        $link = $(e.target);

                    $link.closest('.saleBox__productRow').remove();
                },
                'submit .saleBox__purchaseForm': function(e) {
                    e.preventDefault();
                    var block = this,
                        purchaseFormData = Backbone.Syphon.serialize(block.$purchaseForm[0]),
                        products = [];

                    _.each(purchaseFormData.product, function(product, index) {
                        products.push({
                            product: product,
                            quantity: purchaseFormData.quantity[index],
                            sellingPrice: purchaseFormData.sellingPrice[index]
                        });
                    });

                    block.purchaseModel = new PurchaseModel({
                        products: products
                    });

                    block.purchaseModel.save();
                },
                'submit .saleBox__productForm': function(e) {
                    e.preventDefault();
                    var block = this;

                    block.$productRow.clone().appendTo(block.$purchaseTable.find('tbody'));
                    block.$productForm.find('input').val('');
                }
            },
            autocompleteToInput: function($input) {
                var name = $input.attr('lh_product_autocomplete');
                $input.autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: baseApiUrl + "/products/" + name + "/search.json",
                            dataType: "json",
                            data: {
                                query: request.term
                            },
                            success: function(data) {
                                response($.map(data, function(item) {
                                    return {
                                        label: item[name],
                                        product: item
                                    }
                                }));
                            }
                        })
                    },
                    minLength: 3,
                    select: function(event, ui) {
                        $(this).parents("form").find("[lh_product_autocomplete='name']").val(ui.item.product.name);
                        $(this).parents("form").find("[name='product[]']").val(ui.item.product.id);

                        $(this).parents("form").find("[name='quantity[]']").focus();
                    }
                });
                $input.keyup(function(event) {
                    var keyCode = $.ui.keyCode;
                    switch (event.keyCode) {
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
                            var term = $(this).autocomplete('getTerm');
                            if (term != $(this).val()) {
                                $(this).parents("form").find("[name='product']").val('');
                            }
                    }
                });
            },
            _saveElements: function() {
                var block = this;

                block.$purchaseForm = block.$el.find('.saleBox__purchaseForm');
                block.$purchaseTable = block.$purchaseForm.find('.table');
                block.$productForm = block.$el.find('.saleBox__productForm');
                block.$productRow = block.$productForm.find('.saleBox__productRow');

                block.autocompleteToInput(block.$productForm.find('[lh_product_autocomplete]'));
            }
        });
    }
);