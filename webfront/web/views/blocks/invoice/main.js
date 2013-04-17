define(
    [
        '/models/invoice.js',
        '/models/invoiceProduct.js',
        '/collections/invoiceProducts.js',
        '/utils/main.js',
        '/views/blocks/form/main.js',
        './templates.js'
    ],
    function(invoiceModel, invoiceProduct, invoiceProductsCollection, utils, form, templates) {
        return Backbone.Block.extend({
            initialize: function() {
                var block = this;

                block.render();

                block.$head = block.$el.find('.invoice__head');
                block.$table = block.$el.find('.invoice__table');
                block.$footer = block.$el.find('.invoice__footer');
                block.form = new form({
                    el: block.el.getElementsByClassName('invoice__addForm')
                });

                block.autocompleteToInput('name');
                block.autocompleteToInput('sku');
                block.autocompleteToInput('barcode');


                block.invoiceProductsCollection = new invoiceProductsCollection({
                    invoiceId: block.invoiceId
                });

                block.invoiceModel = new invoiceModel({
                    id: block.invoiceId
                });

                block.invoiceModel.fetch();
                block.invoiceProductsCollection.fetch();

                block
                    .listenTo(block.invoiceModel, 'sync change', function() {
                        block.$head.html(block.tpl.head({
                            block: block
                        }));
                        block.$footer.html(block.tpl.footer({
                            block: block
                        }));
                    });

                block
                    .listenTo(block.invoiceProductsCollection, {
                        sync: function() {
                            block.renderTable();
                        },
                        add: function(model) {
                            block.renderTable();
                            block.invoiceModel.set(model.toJSON().invoice);
                        }
                    });
            },
            events: {
                'submit .invoice__addForm': function(e) {
                    e.preventDefault();
                    var block = this,
                        productData = Backbone.Syphon.serialize(block);

                    block.addProduct(productData);
                },
                'click .invoice__editLink': function(e) {
                    e.preventDefault();
                    var block = this;

                    block.set("editMode", true);
                },
                'click .invoice__stopEditLink, .invoice__stopEditButton': function(e) {
                    e.preventDefault();
                    var block = this;

                    var notEmptyForm = false;
                    block.$el.find("input").each(function() {
                        if ($(this).val()) {
                            notEmptyForm = true;
                        }
                    });

                    if (notEmptyForm) {
                        alert("У вас есть не сохранённые данные");
                    } else {
                        block.set("editMode", false);
                    }
                },
                'click .invoice__editable': function(e) {
                    e.preventDefault();
                    var block = this;

                    if (block.editMode && !block.dataEditing) {
                        block.showDataInput($(e.target));
                    }
                }
            },
            editMode: false,
            dataEditing: false,
            'set editMode': function(val) {
                var block = this;

                if (val) {
                    block.$el.addClass('invoice_editMode');
                } else {
                    block.$el.removeClass('invoice_editMode');
                }
            },
            'set dataEditing': function(val) {
                var block = this;

                block.disableAddForm(val);

                if (val) {
                    block.$el.addClass('invoice_dataEditing');
                } else {
                    block.$el.removeClass('invoice_dataEditing');
                }
            },
            renderTable: function() {
                var block = this;

                block.$table.html(block.tpl.table({
                    block: block
                }));
            },
            disableAddForm: function(disabled){
                var block = this;
                if (disabled){
                    block.form.$el.find('[type=submit]').closest('.button').addClass('button_disabled');
                } else {
                    block.form.$el.find('[type=submit]').closest('.button').removeClass('button_disabled');
                }
            },
            autocompleteToInput: function(name) {
                var input = this.$el.find("[lh_product_autocomplete='" + name + "']");
                input.autocomplete({
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
                        $(this).parents("form").find("[lh_product_autocomplete='sku']").val(ui.item.product.sku);
                        $(this).parents("form").find("[lh_product_autocomplete='barcode']").val(ui.item.product.barcode);
                        $(this).parents("form").find("[name='product']").val(ui.item.product.id);

                        $(this).parents("form").find("[name='quantity']").focus();
                    }
                });
                input.keyup(function(event) {
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
                            if (null != term && term != $(this).val()) {
                                var inputs = ['name', 'sku', 'barcode'];
                                for (var i in inputs) {
                                    if (inputs[i] != name) {
                                        $(this).parents("form").find("[lh_product_autocomplete='" + inputs[i] + "']").val('').trigger('input');
                                    }
                                }
                                $(this).parents("form").find("[name='product']").val('');
                            }
                    }
                });
            },
            utils: utils,
            tpl: templates,
            addProduct: function(productData) {
                var block = this,
                    newProduct = new invoiceProduct({
                        invoiceId: block.invoiceId
                    });

                newProduct.set(productData);

                newProduct.save({}, {
                    error: function(model, res) {
                        block.showErrors(JSON.parse(res.responseText));
                    },
                    success: function(model) {
                        block.invoiceProductsCollection.push(model);
                        block.form.clear();
                    }
                });
            },
            showErrors: function(data) {
                var block = this;

                block.form.removeErrors();

                _.each(data.children, function(data, field) {
                    var fieldErrors = data.errors.join(', ');

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

                });
            },
            showDataInput: function($el) {
                var block = this,
                    $dataElement = $el,
                    $dataRow = $el.closest('.invoice__dataRow'),
                    dataInputControls = block.tpl.dataInputControls();

                block.set('dataEditing', true);

                if (!$dataElement.attr('name')) {
                    $dataElement = $el.find('[name]');
                }

                if ($dataRow.prop('tagName') === 'TR'){
                    dataInputControls = '<tr><td class="invoice__dataInputControlsTd" colspan="' + $dataRow.find('td').length + '">' + dataInputControls + '</td></tr>'
                }

                $dataElement.append(block.tpl.dataInput({
                    $dataElement: $dataElement
                }));

                $dataRow.after(dataInputControls);

                $dataElement.find('.inputText')
                    .css({
                        width: $dataElement.width(),
                        'font': $dataElement.css('font')
                    })
                    .focus();
            }
        });
    }
);