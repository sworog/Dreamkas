define(function(require) {
        //requirements
        var Block = require('kit/core/block'),
            InputDate = require('kit/blocks/inputDate/inputDate'),
            Form_invoiceProduct = require('blocks/form/form_invoiceProduct/form_invoiceProduct'),
            Table_invoiceProducts = require('blocks/table/table_invoiceProducts/table_invoiceProducts'),
            cookie = require('cookies'),
            form2js = require('form2js');

        return Block.extend({
            __name__: 'invoice',
            editMode: false,
            dataEditing: false,
            template: require('tpl!blocks/invoice/templates/index.html'),
            templates: {
                index: require('tpl!blocks/invoice/templates/index.html'),
                dataInput: require('tpl!blocks/invoice/templates/dataInput.html'),
                dataInputAutocomplete: require('tpl!blocks/invoice/templates/dataInputAutocomplete.html'),
                dataInputControls: require('tpl!blocks/invoice/templates/dataInputControls.html'),
                footer: require('tpl!blocks/invoice/templates/footer.html'),
                head: require('tpl!blocks/invoice/templates/head.html'),
                removeConfirm: require('tpl!blocks/invoice/templates/removeConfirm.html')
            },
            listeners: {
                invoiceModel: {
                    change: function(){
                        var block = this;

                        block.renderFooter();
                    }
                },
                invoiceProductsCollection: {
                    add: function(model) {
                        var block = this;
                        block.invoiceModel.set(model.get('invoice'));
                    },
                    change: function(model) {
                        var block = this;
                        block.invoiceModel.set(model.get('invoice'));
                    },
                    destroy: function() {
                        var block = this;
                        block.invoiceModel.fetch();
                    },
                    reset: function(){
                        var block = this;
                        block.productsTable.render();
                    }
                }
            },
            events: {
                'click .invoice__removeLink': function(e) {
                    e.preventDefault();
                    var block = this,
                        invoiceProductId = $(e.target).closest('.invoice__dataRow').attr('invoice-product-id');

                    block.showRemoveConfirm(invoiceProductId);
                },
                'click .invoice__removeCancel': function(e) {
                    e.preventDefault();
                    var block = this,
                        invoiceProductId = $(e.target).closest('.invoice__removeConfirmRow').attr('invoice-product-id');

                    block.hideRemoveConfirm(invoiceProductId);
                },
                'click .invoice__removeConfirmButton': function(e) {
                    e.preventDefault();
                    var block = this,
                        invoiceProductId = $(e.target).closest('.invoice__removeConfirmRow').attr('invoice-product-id');

                    block.removeInvoiceProduct(invoiceProductId);
                    block.set('dataEditing', false);
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

                    block.productForm.$el.find("input").each(function() {
                        if ($(this).val()) {
                            notEmptyForm = true;
                        }
                    });

                    if (notEmptyForm || block.dataEditing) {
                        alert("У вас есть несохранённые данные");
                    } else {
                        block.set("editMode", false);
                    }
                },
                'click .invoice__editable': function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var block = this;

                    if (block.editMode && !block.dataEditing) {
                        block.showDataInput($(e.currentTarget));
                    }
                },
                'submit .invoice__productsTable .invoice__dataInput': function(e) {
                    e.preventDefault();
                    var block = this,
                        $form = $(e.target),
                        data = form2js(e.target, '.', false),
                        invoiceProductId = $form.closest('[invoice-product-id]').attr('invoice-product-id'),
                        invoiceProduct = block.invoiceProductsCollection.get(invoiceProductId),
                        $submitButton = $('[form*="' + $form.attr('id') + '"]').closest('.button').add($form.find('[type="submit"]').closest('.button'));

                    block.removeInlineErrors();
                    $submitButton.addClass('preloader_rows');

                    invoiceProduct.save(data, {
                        success: function() {
                            block.set('dataEditing', false);
                            block.productsTable.render();
                        },
                        error: function(data, res) {
                            block.showInlineErrors(JSON.parse(res.responseText));
                            $submitButton.removeClass('preloader_rows');
                        }
                    });
                },
                'submit .invoice__head .invoice__dataInput': function(e) {
                    e.preventDefault();
                    var block = this,
                        $form = $(e.target),
                        data = form2js(e.target, '.', false),
                        $submitButton = $('[form*="' + $form.attr('id') + '"]').closest('.button').add($form.find('[type="submit"]').closest('.button'));

                    block.removeInlineErrors();
                    $submitButton.addClass('preloader_rows');

                    block.invoiceModel.save(data, {
                        success: function() {
                            block.renderHead();
                            block.set('dataEditing', false);
                            $submitButton.removeClass('preloader_rows');
                        },
                        error: function(data, res) {
                            block.showInlineErrors(JSON.parse(res.responseText));
                            $submitButton.removeClass('preloader_rows');
                        }
                    });
                },
                'click .invoice__dataInputCancel': function(e) {
                    var block = this;
                    e.preventDefault();
                    block.removeDataInput();
                },
                'change .invoice__includesVATCheckbox': function(e){
                    var block = this,
                        $checkbox = $(e.target),
                        $label = $checkbox.closest('label');

                    $label.addClass('preloader_spinner');

                    var save = block.invoiceModel.save({
                        includesVAT: $checkbox.is(':checked')
                    });

                    save.done(function(){
                        var products = _.map(block.invoiceModel.get('products'), function(product){
                            product.invoice = block.invoiceModel.toJSON();
                            return product;
                        });

                        block.invoiceProductsCollection.reset(products);
                        $label.removeClass('preloader_spinner');
                    });
                }
            },
            initialize: function() {
                var block = this;

                block.set('editMode', block.editMode);

                block.productForm = new Form_invoiceProduct({
                    invoiceProductsCollection: block.invoiceProductsCollection,
                    el: block.el.getElementsByClassName('invoice__productForm')
                });

                block.productsTable = new Table_invoiceProducts({
                    collection: block.invoiceProductsCollection,
                    el: block.el.getElementsByClassName('invoice__productsTable')
                });
            },
            'set:editMode': function(val) {
                var block = this;

                if (val) {
                    block.$el.addClass('invoice_editMode');
                } else {
                    block.$el.removeClass('invoice_editMode');
                }

                block.$('[name="includesVAT"]').prop('disabled', !val);
            },
            'set:dataEditing': function(val) {
                var block = this;

                block.productForm.disable(val);

                if (val) {
                    block.$el.addClass('invoice_dataEditing');
                } else {
                    block.$el.removeClass('invoice_dataEditing');
                }

                block.$('[name="includesVAT"]').prop('disabled', val);
            },
            showRemoveConfirm: function(invoiceProductId) {
                var block = this,
                    $invoiceProductRow = block.$productsTable.find('.invoice__dataRow[invoice-product-id="' + invoiceProductId + '"]');

                block.hideRemoveConfirms();
                block.set('dataEditing', true);

                $invoiceProductRow
                    .after(block.templates.removeConfirm({
                        invoiceProductId: invoiceProductId
                    }))
                    .hide();
            },
            hideRemoveConfirm: function(invoiceProductId) {
                var block = this,
                    $invoiceProductRow = block.$productsTable.find('.invoice__dataRow[invoice-product-id="' + invoiceProductId + '"]'),
                    $removeConfirmRow = block.$productsTable.find('.invoice__removeConfirmRow[invoice-product-id="' + invoiceProductId + '"]');

                $removeConfirmRow.remove();
                $invoiceProductRow.show();
                block.set('dataEditing', false);
            },
            hideRemoveConfirms: function() {
                var block = this,
                    $invoiceProductRow = block.$productsTable.find('.invoice__dataRow:hidden'),
                    $removeConfirmRows = block.$productsTable.find('.invoice__removeConfirmRow');

                $invoiceProductRow.show();
                $removeConfirmRows.remove();
            },
            removeInvoiceProduct: function(invoiceProductId) {
                var block = this,
                    invoiceProductModel = block.invoiceProductsCollection.get(invoiceProductId);

                invoiceProductModel.destroy({
                    wait: true
                });
            },
            renderHead: function(){
                var block = this;

                block.$head.html(block.templates.head(block));
            },
            renderFooter: function(){
                var block = this;

                block.$footer.html(block.templates.footer(block));
            },
            showInlineErrors: function(data) {
                var block = this,
                    $input = block.$el.find('.invoice__dataInput .inputText'),
                    $inputControls = block.$el.find('.invoice__dataInputControls');

                $input.addClass('inputText_error');

                _.each(data.children, function(data) {
                    var fieldErrors;

                    if (data.errors) {
                        fieldErrors = data.errors.join(', ');
                        $inputControls.attr("data-error", fieldErrors);

                    }
                });
            },
            removeInlineErrors: function() {
                var block = this,
                    $input = block.$el.find('.invoice__dataInput .inputText'),
                    $inputControls = block.$el.find('.invoice__dataInputControls');

                $input.removeClass('.inputText_error');
                $inputControls.removeAttr('data-error');
            },
            showDataInput: function($el) {
                var block = this,
                    $invoiceProduct = $el.closest('[invoice-product-id]'),
                    model = $invoiceProduct.length ? block.invoiceProductsCollection.get($invoiceProduct.attr('invoice-product-id')) : block.invoiceModel,
                    $dataElement = $el,
                    $dataRow = $el.closest('.invoice__dataRow'),
                    dataInputControls = block.templates.dataInputControls();

                block.set('dataEditing', true);

                if (!$dataElement.attr('model-attribute')) {
                    $dataElement = $el.find('[model-attribute]');
                }

                if ($dataRow.prop('tagName') === 'TR') {
                    dataInputControls = '<tr class="invoice__dataInputControlsTr"><td class="invoice__dataInputControlsTd" colspan="' + $dataRow.find('td').length + '">' + dataInputControls + '</td></tr>'
                }

                switch ($dataElement.attr('model-attribute')) {
                    case 'product':
                        $dataElement.append(block.templates.dataInputAutocomplete({
                            $dataElement: $dataElement,
                            model: model
                        }));
                        this.autocompleteToInput($dataElement.find("[lh_product_autocomplete]"));
                        break;

                    case 'quantityElement':
                        $dataElement.append(block.templates.dataInput({
                            $dataElement: $dataElement,
                            model: model,
                            name: 'quantity'
                        }));
                        break;

                    case 'acceptanceDate':
                        $dataElement.append(block.templates.dataInput({
                            $dataElement: $dataElement,
                            model: model
                        }));
                        new InputDate({
                            el: $dataElement.find('.inputText')[0]
                        });
                        break;

                    case 'supplierInvoiceDate':
                        $dataElement.append(block.templates.dataInput({
                            $dataElement: $dataElement,
                            model: model
                        }));
                        new InputDate({
                            el: $dataElement.find('.inputText')[0],
                            noTime: true
                        });
                        break

                    default:
                        $dataElement.append(block.templates.dataInput({
                            $dataElement: $dataElement,
                            model: model
                        }));
                        break;
                }

                $dataRow.after(dataInputControls);

                $dataElement.find('.inputText')
                    .css({
                        width: $dataElement.width(),
                        'font': $dataElement.css('font')
                    })
                    .focus();
            },
            removeDataInput: function() {
                var block = this;

                block.$el.find('.invoice__dataInput').remove();
                block.$el.find('.invoice__dataInputControls').remove();
                block.$el.find('.invoice__dataInputControlsTr').remove();

                block.set('dataEditing', false);
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
            }
        });
    }
);