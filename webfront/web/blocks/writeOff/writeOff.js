define(
    [
        '/kit/block.js',
        '/models/writeOff.js',
        '/collections/writeOffProducts.js',
        '/helpers/helpers.js',
        './addProductForm.js',
        './tpl/tpl.js'
    ],
    function(Block, WriteOffModel, WriteOffProductCollection, helpers, AddProductForm, tpl) {
        return Block.extend({
            editMode: false,
            dataEditing: false,
            tpl: tpl,

            initialize: function() {
                var block = this;

                block.writeOffProductCollection = new WriteOffProductCollection({
                    writeOffId: block.writeOffId
                });

                block.writeOffModel = new WriteOffModel({
                    id: block.writeOffId
                });

                block.writeOffModel.fetch();
                block.writeOffProductCollection.fetch();

                block.render();

                block.set('editMode', block.editMode);

                block.$head = block.$el.find('.writeOff__head');
                block.$table = block.$el.find('.writeOff__table');
                block.$footer = block.$el.find('.writeOff__footer');

                block.addForm = new AddProductForm({
                    writeOffId: block.writeOffId,
                    el: block.el.getElementsByClassName('writeOff__addProductForm')
                });

                block.listenTo(block.addForm, {
                    successSubmit: function(model){
                        block.writeOffProductCollection.push(model);
                        block.addForm.clear();
                    }
                });

                block.listenTo(block.writeOffModel, 'sync change', function() {
                    block.$head.html(block.tpl.head({
                        block: block
                    }));
                    block.$footer.html(block.tpl.footer({
                        block: block
                    }));
                });

                block.listenTo(block.writeOffProductCollection, {
                    sync: function() {
                        block.renderTable();
                    },
                    add: function(model) {
                        block.renderTable();
                        block.writeOffModel.set(model.toJSON().writeOff);
                    },
                    change: function(model) {
                        block.writeOffModel.set(model.toJSON().writeOff);
                    },
                    destroy: function() {
                        block.renderTable();
                        block.writeOffModel.fetch();
                    }
                });
            },
            events: {
                'click .writeOff__removeLink': function(e) {
                    e.preventDefault();
                    var block = this,
                        writeOffProductId = $(e.target).closest('.writeOff__dataRow').attr('writeOff-product-id');

                    block.showRemoveConfirm(writeOffProductId);
                },
                'click .writeOff__removeCancel': function(e) {
                    e.preventDefault();
                    var block = this,
                        writeOffProductId = $(e.target).closest('.writeOff__removeConfirmRow').attr('writeOff-product-id');

                    block.hideRemoveConfirm(writeOffProductId);
                },
                'click .writeOff__removeConfirmButton': function(e) {
                    e.preventDefault();
                    var block = this,
                        writeOffProductId = $(e.target).closest('.writeOff__removeConfirmRow').attr('writeOff-product-id');

                    block.removeWriteOffProduct(writeOffProductId);
                    block.set('dataEditing', false);
                },
                'click .writeOff__editLink': function(e) {
                    e.preventDefault();
                    var block = this;

                    block.set("editMode", true);
                },
                'click .writeOff__stopEditLink, .writeOff__stopEditButton': function(e) {
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
                'click .writeOff__editable': function(e) {
                    e.preventDefault();
                    var block = this;

                    if (block.editMode && !block.dataEditing) {
                        block.showDataInput($(e.currentTarget));
                    }
                },
                'submit .writeOff__table .writeOff__dataInput': function(e) {
                    e.preventDefault();
                    var block = this,
                        data = Backbone.Syphon.serialize(e.target),
                        writeOffProductId = $(e.target).closest('[writeOff-product-id]').attr('writeOff-product-id'),
                        writeOffProduct = block.writeOffProductCollection.get(writeOffProductId),
                        $submitButton = $(e.target).find('[type="submit"]').closest('.button');

                    block.removeInlineErrors();
                    $submitButton.addClass('preloader');

                    writeOffProduct.save(data, {
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
                'submit .writeOff__head .writeOff__dataInput': function(e) {
                    e.preventDefault();
                    var block = this,
                        data = Backbone.Syphon.serialize(e.target),
                        $submitButton = $(e.target).find('[type="submit"]').closest('.button');

                    block.removeInlineErrors();
                    $submitButton.addClass('preloader');

                    block.writeOffModel.save(data, {
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
                'click .writeOff__dataInputSave': function(e) {
                    e.preventDefault();
                    var block = this,
                        $dataInputForm = block.$el.find('.writeOff__dataInput');

                    $dataInputForm.submit();
                },
                'click .writeOff__dataInputCancel': function(e) {
                    var block = this;
                    e.preventDefault();
                    block.removeDataInput();
                }
            },
            'set:editMode': function(val) {
                var block = this;

                if (val) {
                    block.$el.addClass('writeOff_editMode');
                } else {
                    block.$el.removeClass('writeOff_editMode');
                }
            },
            'set:dataEditing': function(val) {
                var block = this;

                block.addForm.disable(val);

                if (val) {
                    block.$el.addClass('writeOff_dataEditing');
                } else {
                    block.$el.removeClass('writeOff_dataEditing');
                }
            },
            showRemoveConfirm: function(writeOffProductId) {
                var block = this,
                    $writeOffProductRow = block.$table.find('.writeOff__dataRow[writeOff-product-id="' + writeOffProductId + '"]');

                block.hideRemoveConfirms();
                block.set('dataEditing', true);

                $writeOffProductRow
                    .after(block.tpl.removeConfirm({
                        writeOffProductId: writeOffProductId
                    }))
                    .hide();
            },
            hideRemoveConfirm: function(writeOffProductId) {
                var block = this,
                    $writeOffProductRow = block.$table.find('.writeOff__dataRow[writeOff-product-id="' + writeOffProductId + '"]'),
                    $removeConfirmRow = block.$table.find('.writeOff__removeConfirmRow[writeOff-product-id="' + writeOffProductId + '"]');

                $removeConfirmRow.remove();
                $writeOffProductRow.show();
                block.set('dataEditing', false);
            },
            hideRemoveConfirms: function() {
                var block = this,
                    $writeOffProductRow = block.$table.find('.writeOff__dataRow:hidden'),
                    $removeConfirmRows = block.$table.find('.writeOff__removeConfirmRow');

                $writeOffProductRow.show();
                $removeConfirmRows.remove();
            },
            removeWriteOffProduct: function(writeOffProductId) {
                var block = this,
                    writeOffProductModel = block.writeOffProductCollection.get(writeOffProductId);

                writeOffProductModel.destroy({
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
                    $input = block.$el.find('.writeOff__dataInput .inputText'),
                    $inputControls = block.$el.find('.writeOff__dataInputControls');

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
                    $input = block.$el.find('.writeOff__dataInput .inputText'),
                    $inputControls = block.$el.find('.writeOff__dataInputControls');

                $input.removeClass('.inputText_error');
                $inputControls.removeAttr('lh_field_error');
            },
            datePickerToInput: function($input) {
                var block = this;

                $input.mask('99.99.9999');

                var options = {
                    dateFormat: block.writeOffModel.dateFormat
                };

                $input.datepicker(options);
            },
            showDataInput: function($el) {
                var block = this,
                    $dataElement = $el,
                    $dataRow = $el.closest('.writeOff__dataRow'),
                    dataInputControls = block.tpl.dataInputControls();

                block.set('dataEditing', true);

                if (!$dataElement.attr('model-attr')) {
                    $dataElement = $el.find('[model-attr]');
                }

                if ($dataRow.prop('tagName') === 'TR') {
                    dataInputControls = '<tr class="writeOff__dataInputControlsTr"><td class="writeOff__dataInputControlsTd" colspan="' + $dataRow.find('td').length + '">' + dataInputControls + '</td></tr>'
                }

                switch ($dataElement.attr('model-attr')) {
                    case 'product':
                        $dataElement.append(block.tpl.dataInputAutocomplete({
                            $dataElement: $dataElement
                        }));
                        block.autocompleteToInput($dataElement.find("[lh_product_autocomplete]"));
                        break;

                    case 'date':
                        $dataElement.append(block.tpl.dataInput({
                            $dataElement: $dataElement
                        }));
                        block.datePickerToInput($dataElement.find("form [name]"), false);
                        break;

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

                block.$el.find('.writeOff__dataInput').remove();
                block.$el.find('.writeOff__dataInputControls').remove();
                block.$el.find('.writeOff__dataInputControlsTr').remove();

                block.set('dataEditing', false);
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