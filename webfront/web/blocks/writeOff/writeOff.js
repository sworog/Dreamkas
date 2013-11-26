define(function(require) {
        //requirements
        var Block = require('kit/core/block'),
            InputDate = require('kit/blocks/inputDate/inputDate'),
            Form_writeOffProduct = require('blocks/form/form_writeOffProduct/form_writeOffProduct'),
            Table_writeOffProducts = require('blocks/table/table_writeOffProducts/table_writeOffProducts'),
            cookie = require('kit/libs/cookie'),
            form2js = require('kit/libs/form2js');

        return Block.extend({
            __name__: 'writeOff',
            editMode: false,
            dataEditing: false,
            template: require('tpl!blocks/writeOff/templates/index.html'),
            templates: {
                index: require('tpl!blocks/writeOff/templates/index.html'),
                dataInput: require('tpl!blocks/writeOff/templates/dataInput.html'),
                dataInputAutocomplete: require('tpl!blocks/writeOff/templates/dataInputAutocomplete.html'),
                dataInputControls: require('tpl!blocks/writeOff/templates/dataInputControls.html'),
                footer: require('tpl!blocks/writeOff/templates/footer.html'),
                head: require('tpl!blocks/writeOff/templates/head.html'),
                removeConfirm: require('tpl!blocks/writeOff/templates/removeConfirm.html')
            },
            listeners: {
                writeOffModel: {
                    change: function(){
                        var block = this;

                        block.renderHead();
                        block.renderFooter();
                    }
                },
                writeOffProductsCollection: {
                    add: function(model) {
                        var block = this;
                        block.writeOffModel.set(model.toJSON().writeOff);
                    },
                    change: function(model) {
                        var block = this;
                        block.writeOffModel.set(model.toJSON().writeOff);
                    },
                    destroy: function() {
                        var block = this;
                        block.writeOffModel.fetch();
                    },
                    reset: function(){
                        var block = this;
                        block.productsTable.render();
                    }
                }
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

                    block.productForm.$el.find('.inputText').each(function() {
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
                'click .writeOff__editable': function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var block = this;

                    if (block.editMode && !block.dataEditing) {
                        block.showDataInput($(e.currentTarget));
                    }
                },
                'submit .writeOff__productsTable .writeOff__dataInput': function(e) {
                    e.preventDefault();
                    var block = this,
                        $form = $(e.target),
                        data = form2js(e.target, '.', false),
                        writeOffProductId = $form.closest('[writeOff-product-id]').attr('writeOff-product-id'),
                        writeOffProduct = block.writeOffProductsCollection.get(writeOffProductId),
                        $submitButton = $('[form*="' + $form.attr('id') + '"]').closest('.button').add($form.find('[type="submit"]').closest('.button'));

                    block.removeInlineErrors();
                    $submitButton.addClass('preloader_rows');

                    writeOffProduct.set(data, {
                        silent: true
                    });

                    writeOffProduct.save(null, {
                        success: function() {
                            block.set('dataEditing', false);
                            $submitButton.removeClass('preloader_rows');
                        },
                        error: function(data, res) {
                            block.showInlineErrors(JSON.parse(res.responseText));
                            $submitButton.removeClass('preloader_rows');
                        }
                    });
                },
                'submit .writeOff__head .writeOff__dataInput': function(e) {
                    e.preventDefault();
                    var block = this,
                        data = form2js(e.target, '.', false),
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
            initialize: function() {
                var block = this;

                block.set('editMode', block.editMode);

                block.productForm = new Form_writeOffProduct({
                    writeOffProductsCollection: block.writeOffProductsCollection,
                    el: block.el.getElementsByClassName('writeOff__productForm')
                });

                block.productsTable = new Table_writeOffProducts({
                    collection: block.writeOffProductsCollection,
                    el: block.el.getElementsByClassName('writeOff__productsTable')
                });
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

                block.productForm.disable(val);

                if (val) {
                    block.$el.addClass('writeOff_dataEditing');
                } else {
                    block.$el.removeClass('writeOff_dataEditing');
                }
            },
            showRemoveConfirm: function(writeOffProductId) {
                var block = this,
                    $writeOffProductRow = block.$productsTable.find('.writeOff__dataRow[writeOff-product-id="' + writeOffProductId + '"]');

                block.hideRemoveConfirms();
                block.set('dataEditing', true);

                $writeOffProductRow
                    .after(block.templates.removeConfirm({
                        writeOffProductId: writeOffProductId
                    }))
                    .hide();
            },
            hideRemoveConfirm: function(writeOffProductId) {
                var block = this,
                    $writeOffProductRow = block.$productsTable.find('.writeOff__dataRow[writeOff-product-id="' + writeOffProductId + '"]'),
                    $removeConfirmRow = block.$productsTable.find('.writeOff__removeConfirmRow[writeOff-product-id="' + writeOffProductId + '"]');

                $removeConfirmRow.remove();
                $writeOffProductRow.show();
                block.set('dataEditing', false);
            },
            hideRemoveConfirms: function() {
                var block = this,
                    $writeOffProductRow = block.$productsTable.find('.writeOff__dataRow:hidden'),
                    $removeConfirmRows = block.$productsTable.find('.writeOff__removeConfirmRow');

                $writeOffProductRow.show();
                $removeConfirmRows.remove();
            },
            removeWriteOffProduct: function(writeOffProductId) {
                var block = this,
                    writeOffProductModel = block.writeOffProductsCollection.get(writeOffProductId);

                writeOffProductModel.destroy({
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
                    $input = block.$el.find('.writeOff__dataInput .inputText'),
                    $inputControls = block.$el.find('.writeOff__dataInputControls');

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
                    $input = block.$el.find('.writeOff__dataInput .inputText'),
                    $inputControls = block.$el.find('.writeOff__dataInputControls');

                $input.removeClass('.inputText_error');
                $inputControls.removeAttr('data-error');
            },
            showDataInput: function($el) {
                var block = this,
                    $dataElement = $el,
                    $dataRow = $el.closest('.writeOff__dataRow'),
                    dataInputControls = block.templates.dataInputControls();

                block.set('dataEditing', true);

                if (!$dataElement.attr('model-attribute')) {
                    $dataElement = $el.find('[model-attribute]');
                }

                if ($dataRow.prop('tagName') === 'TR') {
                    dataInputControls = '<tr class="writeOff__dataInputControlsTr"><td class="writeOff__dataInputControlsTd" colspan="' + $dataRow.find('td').length + '">' + dataInputControls + '</td></tr>'
                }

                switch ($dataElement.attr('model-attribute')) {
                    case 'product':
                        $dataElement.append(block.templates.dataInputAutocomplete({
                            $dataElement: $dataElement
                        }));
                        block.autocompleteToInput($dataElement.find("[lh_product_autocomplete]"));
                        break;

                    case 'quantityElement':
                        $dataElement.append(block.templates.dataInput({
                            $dataElement: $dataElement,
                            name: 'quantity'
                        }));
                        break;

                    case 'date':
                        $dataElement.append(block.templates.dataInput({
                            $dataElement: $dataElement
                        }));
                        new InputDate({
                            el: $dataElement.find('.inputText')[0],
                            noTime: true
                        });
                        break;

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
                        $(this).parents("form").find("[name='price']").val(ui.item.product.purchasePrice || ui.item.product.retailPrice);

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