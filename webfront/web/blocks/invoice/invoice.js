define(
    [
        '/kit/block.js',
        '/models/invoice.js',
        '/models/invoiceProduct.js',
        '/collections/invoiceProducts.js',
        '/helpers/helpers.js',
        './addForm.js',
        './tpl/tpl.js'
    ],
    function(block, invoiceModel, invoiceProduct, invoiceProductsCollection, utils, addForm, templates) {
        return block.extend({
            utils: utils,
            tpl: templates,
            editMode: false,
            dataEditing: false,

            initialize: function() {
                var block = this;

                block.render();

                block.$head = block.$el.find('.invoice__head');
                block.$table = block.$el.find('.invoice__table');
                block.$footer = block.$el.find('.invoice__footer');

                block.addForm = new addForm({
                    el: block.el.getElementsByClassName('invoice__addForm')
                });

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

                    block.addForm.$el.find("input").each(function() {
                        if ($(this).val()) {
                            notEmptyForm = true;
                        }
                    });


                    if (notEmptyForm || block.dataEditing) {
                        alert("У вас есть не сохранённые данные");
                    } else {
                        block.set("editMode", false);
                    }
                },
                'click .invoice__editable': function(e) {
                    e.preventDefault();
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
                        invoiceProduct = block.invoiceProductsCollection.get(invoiceProductId);

                    block.removeInlineErrors();

                    invoiceProduct.save(data, {
                        success: function() {
                            block.set('dataEditing', false);
                        },
                        error: function(data, res) {
                            block.showInlineErrors(JSON.parse(res.responseText));
                        }
                    });
                },
                'submit .invoice__head .invoice__dataInput': function(e) {
                    e.preventDefault();
                    var block = this,
                        data = Backbone.Syphon.serialize(e.target);

                    block.removeInlineErrors();

                    block.invoiceModel.save(data, {
                        success: function() {
                            block.set('dataEditing', false);
                        },
                        error: function(data, res) {
                            block.showInlineErrors(JSON.parse(res.responseText));
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
                    .after(block.tpl.removeConfirm({
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
                    invoiceProductModel = block.invoiceProductsCollection.get(invoiceProductId);

                invoiceProductModel.destroy({
                    wait: true
                });
            },
            renderTable: function() {
                var block = this;

                block.$table.html(block.tpl.table({
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
            dateTimePickerToInput: function($input, currentTime) {
                var block = this;
                $input.mask('99.99.9999 99:99');

                var onClose = function(dateText, datepicker) {
                    $input.val(dateText);
                }

                var dateTimePickerControl = {
                    create: function(tp_inst, obj, unit, val, min, max, step) {
                        var input = jQuery('<input class="ui-timepicker-input" value="' + val + '" style="width:50%">');
                        input.change(function(e) {
                            if (e.originalEvent !== undefined) {
                                tp_inst._onTimeChange();
                            }
                            tp_inst._onSelectHandler();
                        })
                        input.appendTo(obj);
                        return obj;
                    },
                    options: function(tp_inst, obj, unit, opts, val) {
                    },
                    value: function(tp_inst, obj, unit, val) {
                        if (val !== undefined) {
                            return obj.find('.ui-timepicker-input').val(val);
                        } else {
                            return obj.find('.ui-timepicker-input').val();
                        }
                    }
                };

                var options = {
                    controlType: dateTimePickerControl,
                    onClose: onClose,
                    dateFormat: block.invoiceModel.dateFormat,
                    timeFormat: block.invoiceModel.timeFormat
                }

                $input.datetimepicker(options);

                var currentTime = currentTime || false;

                if (currentTime) {
                    $input.datetimepicker('setDate', new Date())
                }
            },
            datePickerToInput: function($input) {
                var block = this;

                $input.mask('99.99.9999');

                var options = {
                    dateFormat: block.invoiceModel.dateFormat
                };

                $input.datepicker(options);
            },
            addProduct: function(productData) {
                var block = this,
                    newProduct = new invoiceProduct({
                        invoice: {
                            id: block.invoiceId
                        }
                    });

                newProduct.save(productData, {
                    error: function(model, res) {
                        block.addForm.showErrors(JSON.parse(res.responseText));
                    },
                    success: function(model) {
                        block.invoiceProductsCollection.push(model);
                        block.addForm.clear();
                    }
                });
            },
            showDataInput: function($el) {
                var block = this,
                    $dataElement = $el,
                    $dataRow = $el.closest('.invoice__dataRow'),
                    dataInputControls = block.tpl.dataInputControls();

                block.set('dataEditing', true);

                if (!$dataElement.attr('model-attr')) {
                    $dataElement = $el.find('[model-attr]');
                }

                if ($dataRow.prop('tagName') === 'TR') {
                    dataInputControls = '<tr class="invoice__dataInputControlsTr"><td class="invoice__dataInputControlsTd" colspan="' + $dataRow.find('td').length + '">' + dataInputControls + '</td></tr>'
                }

                switch ($dataElement.attr('model-attr')) {
                    case 'product':
                        $dataElement.append(block.tpl.dataInputAutocomplete({
                            $dataElement: $dataElement
                        }));
                        this.autocompleteToInput($dataElement.find("[lh_product_autocomplete]"));
                        break;

                    case 'acceptanceDate':
                        $dataElement.append(block.tpl.dataInput({
                            $dataElement: $dataElement
                        }));
                        block.dateTimePickerToInput($dataElement.find("form [name]"), false);
                        break;

                    case 'supplierInvoiceDate':
                        $dataElement.append(block.tpl.dataInput({
                            $dataElement: $dataElement
                        }));
                        block.datePickerToInput($dataElement.find("form [name]"));
                        break

                    default:
                        $dataElement.append(block.tpl.dataInput({
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
            }
        });
    }
);