define(function(require) {
        //requirements
        var Block = require('kit/block'),
            InputDate = require('kit/blocks/inputDate/inputDate'),
            InvoiceModel = require('models/invoice'),
            InvoiceProductCollection = require('collections/invoiceProducts'),
            AddProductForm = require('blocks/invoice/addProductForm'),
            cookie = require('utils/cookie');

        return Block.extend({
            editMode: false,
            dataEditing: false,
            className: 'invoice',
            templates: {
                index: require('tpl!./templates/invoice.html'),
                dataInput: require('tpl!./templates/dataInput.html'),
                dataInputAutocomplete: require('tpl!./templates/dataInputAutocomplete.html'),
                dataInputControls: require('tpl!./templates/dataInputControls.html'),
                footer: require('tpl!./templates/footer.html'),
                head: require('tpl!./templates/head.html'),
                removeConfirm: require('tpl!./templates/removeConfirm.html'),
                row: require('tpl!./templates/row.html'),
                table: require('tpl!./templates/table.html')
            },

            initialize: function() {
                var block = this;

                block.invoiceProductCollection = new InvoiceProductCollection({
                    invoiceId: block.invoiceId
                });

                block.invoiceModel = new InvoiceModel({
                    id: block.invoiceId
                });

                block.invoiceModel.fetch();
                block.invoiceProductCollection.fetch();

                block.render();

                block.set('editMode', block.editMode);

                block.addForm = new AddProductForm({
                    invoiceId: block.invoiceId,
                    el: block.el.getElementsByClassName('invoice__addProductForm')
                });

                block.listenTo(block.addForm, {
                    successSubmit: function(model){
                        block.invoiceProductCollection.push(model);
                        block.addForm.clear();
                    }
                });

                block.listenTo(block.invoiceModel, 'sync change', function() {
                        block.$head.html(block.templates.head({
                            block: block
                        }));
                        block.$footer.html(block.templates.footer({
                            block: block
                        }));
                    });

                block.listenTo(block.invoiceProductCollection, {
                        sync: function() {
                            block.renderTable();
                        },
                        add: function(model) {
                            block.renderTable();
                            block.invoiceModel.set(model.toJSON().invoice);
                        },
                        change: function(model) {
                            block.invoiceModel.set(model.toJSON().invoice);
                        },
                        destroy: function() {
                            block.renderTable();
                            block.invoiceModel.fetch();
                        }
                    });
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

                    block.addForm.$el.find("input").each(function() {
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
                'submit .invoice__table .invoice__dataInput': function(e) {
                    e.preventDefault();
                    var block = this,
                        data = Backbone.Syphon.serialize(e.target),
                        invoiceProductId = $(e.target).closest('[invoice-product-id]').attr('invoice-product-id'),
                        invoiceProduct = block.invoiceProductCollection.get(invoiceProductId),
                        $submitButton = $(e.target).find('[type="submit"]').closest('.button');

                    block.removeInlineErrors();
                    $submitButton.addClass('preloader');

                    invoiceProduct.save(data, {
                        success: function() {
                            block.set('dataEditing', false);
                            $submitButton.removeClass('preloader');
                        },
                        error: function(data, res) {
                            block.showInlineErrors(JSON.parse(res.responseText));
                            $submitButton.removeClass('preloader');
                        }
                    });
                },
                'submit .invoice__head .invoice__dataInput': function(e) {
                    e.preventDefault();
                    var block = this,
                        data = Backbone.Syphon.serialize(e.target),
                        $submitButton = $(e.target).find('[type="submit"]').closest('.button');

                    block.removeInlineErrors();
                    $submitButton.addClass('preloader');

                    block.invoiceModel.save(data, {
                        success: function() {
                            block.set('dataEditing', false);
                            $submitButton.removeClass('preloader');
                        },
                        error: function(data, res) {
                            block.showInlineErrors(JSON.parse(res.responseText));
                            $submitButton.removeClass('preloader');
                        },
                        wait: true
                    });
                },
                'click .invoice__dataInputSave': function(e) {
                    e.preventDefault();
                    var block = this,
                        $dataInputForm = block.$el.find('.invoice__dataInput');

                    $dataInputForm.submit();
                },
                'click .invoice__dataInputCancel': function(e) {
                    var block = this;
                    e.preventDefault();
                    block.removeDataInput();
                }
            },
            'set:editMode': function(val) {
                var block = this;

                if (val) {
                    block.$el.addClass('invoice_editMode');
                } else {
                    block.$el.removeClass('invoice_editMode');
                }
            },
            'set:dataEditing': function(val) {
                var block = this;

                block.addForm.disable(val);

                if (val) {
                    block.$el.addClass('invoice_dataEditing');
                } else {
                    block.$el.removeClass('invoice_dataEditing');
                }
            },
            showRemoveConfirm: function(invoiceProductId) {
                var block = this,
                    $invoiceProductRow = block.$table.find('.invoice__dataRow[invoice-product-id="' + invoiceProductId + '"]');

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
                    $invoiceProductRow = block.$table.find('.invoice__dataRow[invoice-product-id="' + invoiceProductId + '"]'),
                    $removeConfirmRow = block.$table.find('.invoice__removeConfirmRow[invoice-product-id="' + invoiceProductId + '"]');

                $removeConfirmRow.remove();
                $invoiceProductRow.show();
                block.set('dataEditing', false);
            },
            hideRemoveConfirms: function() {
                var block = this,
                    $invoiceProductRow = block.$table.find('.invoice__dataRow:hidden'),
                    $removeConfirmRows = block.$table.find('.invoice__removeConfirmRow');

                $invoiceProductRow.show();
                $removeConfirmRows.remove();
            },
            removeInvoiceProduct: function(invoiceProductId) {
                var block = this,
                    invoiceProductModel = block.invoiceProductCollection.get(invoiceProductId);

                invoiceProductModel.destroy({
                    wait: true
                });
            },
            renderTable: function() {
                var block = this;

                block.$table.html(block.templates.table({
                    block: block
                }));
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
                        $inputControls.attr("lh_field_error", fieldErrors);

                    }
                });
            },
            removeInlineErrors: function() {
                var block = this,
                    $input = block.$el.find('.invoice__dataInput .inputText'),
                    $inputControls = block.$el.find('.invoice__dataInputControls');

                $input.removeClass('.inputText_error');
                $inputControls.removeAttr('lh_field_error');
            },
            showDataInput: function($el) {
                var block = this,
                    $dataElement = $el,
                    $dataRow = $el.closest('.invoice__dataRow'),
                    dataInputControls = block.templates.dataInputControls();

                block.set('dataEditing', true);

                if (!$dataElement.attr('model-attr')) {
                    $dataElement = $el.find('[model-attr]');
                }

                if ($dataRow.prop('tagName') === 'TR') {
                    dataInputControls = '<tr class="invoice__dataInputControlsTr"><td class="invoice__dataInputControlsTd" colspan="' + $dataRow.find('td').length + '">' + dataInputControls + '</td></tr>'
                }

                switch ($dataElement.attr('model-attr')) {
                    case 'product':
                        $dataElement.append(block.templates.dataInputAutocomplete({
                            $dataElement: $dataElement
                        }));
                        this.autocompleteToInput($dataElement.find("[lh_product_autocomplete]"));
                        break;

                    case 'acceptanceDate':
                        $dataElement.append(block.templates.dataInput({
                            $dataElement: $dataElement
                        }));
                        new InputDate({
                            el: $dataElement.find('.inputText')[0]
                        });
                        break;

                    case 'supplierInvoiceDate':
                        $dataElement.append(block.templates.dataInput({
                            $dataElement: $dataElement
                        }));
                        new InputDate({
                            el: $dataElement.find('.inputText')[0],
                            noTime: true
                        });
                        break

                    default:
                        $dataElement.append(block.templates.dataInput({
                            $dataElement: $dataElement
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