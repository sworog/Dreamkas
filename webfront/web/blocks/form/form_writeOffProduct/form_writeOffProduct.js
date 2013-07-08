define(function(require) {
        //requirements
        var Form = require('blocks/form/form'),
            _ = require('underscore'),
            WriteOffProduct = require('models/writeOffProduct'),
            cookie = require('utils/cookie');

        return Form.extend({
            blockName: 'form_writeOffProduct',
            templates: {
                index: require('tpl!blocks/form/form_writeOffProduct/templates/index.html')
            },

            initialize: function(){
                Form.prototype.initialize.apply(this, arguments);

                var block = this;

                block.autocompleteToInput(block.$el.find("[lh_product_autocomplete='name']"));
                block.autocompleteToInput(block.$el.find("[lh_product_autocomplete='sku']"));
                block.autocompleteToInput(block.$el.find("[lh_product_autocomplete='barcode']"));
            },
            submit: function(){
                var block = this,
                    deferred = $.Deferred(),
                    newProduct = new WriteOffProduct({
                        writeOff: {
                            id: block.writeOffProductsCollection.writeOffId
                        }
                    });

                newProduct.save(block.data, {
                    error: function(model, res) {
                        deferred.reject(JSON.parse(res.responseText));
                    },
                    success: function(model) {
                        block.writeOffProductsCollection.push(model);
                        block.clear();
                        deferred.resolve(model);
                    }
                });

                return deferred.promise();
            },
            showErrors: function(data) {
                var block = this;

                block.removeErrors();

                _.each(data.children, function(data, field) {
                    var fieldErrors;

                    if (data.errors) {
                        fieldErrors = data.errors.join(', ');
                        if (field == 'product') {
                            var productField;
                            if (block.$el.find("[lh_product_autocomplete='barcode']").val()) {
                                productField = 'barcode';
                            } else if (block.$el.find("[lh_product_autocomplete='sku']").val()) {
                                productField = 'sku';
                            } else {
                                productField = 'name';
                            }

                            block.$el.find("[lh_product_autocomplete='" + productField + "']").closest(".form__field").attr("lh_field_error", fieldErrors);
                        } else {
                            block.$el.find("[name='" + field + "']").closest(".form__field").attr("lh_field_error", fieldErrors);
                        }
                    }
                });
            },
            autocompleteToInput: function($input) {
                var name = $input.attr('lh_product_autocomplete');
                $input.autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: LH.baseApiUrl + "/products/" + name + "/search.json",
                            dataType: "json",
                            data: {
                                query: request.term
                            },
                            headers: {
                                Authorization: 'Bearer ' + cookie.get('token')
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
                        $(this).parents("form").find("[lh_product_autocomplete='sku']").val(ui.item.product.sku);
                        $(this).parents("form").find("[lh_product_autocomplete='barcode']").val(ui.item.product.barcode);
                        $(this).parents("form").find("[name='product']").val(ui.item.product.id);
                        $(this).parents("form").find("[name='price']").val(ui.item.product.retailPrice || ui.item.product.purchasePrice);

                        $(this).parents("form").find("[name='quantity']").focus();
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
                                var inputs = ['name', 'sku', 'barcode', 'price'];
                                for (var i in inputs) {
                                    if (inputs[i] != name) {
                                        $(this).parents("form").find("[lh_product_autocomplete='" + inputs[i] + "']").val('').trigger('input');
                                    }
                                }
                                $(this).parents("form").find("[name='product']").val('');
                            }
                    }
                });
            }
        });
    }
);