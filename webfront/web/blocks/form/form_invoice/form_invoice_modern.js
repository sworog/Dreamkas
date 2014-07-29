define(function(require) {
    var Form = require('kit/form/form'),
        InvoiceProductModel = require('models/invoiceProduct/invoiceProduct'),
        Select_suppliers = require('blocks/select/select_suppliers/select_suppliers'),
        router = require('router'),
        cookies = require('cookies'),
        form2js = require('form2js'),
        invoiceProductTable = require('ejs!blocks/form/form_invoice/invoiceProductTable.ejs');

    return Form.extend({
        currentInvoiceProductTarget: null,
        events: {
            'typeahead:selected': function(event, product, sugg) {
                var block = this;

                block.currentInvoiceProductTarget = product;

                $(event.currentTarget).find('[name=priceEntered]').focus();
            },
            'keyup input[name=product]': function() {
                this.currentInvoiceProductTarget = null;
            },
            'click .addInvoiceProduct': function(event) {
                var block = this;
                if (this.currentInvoiceProductTarget != null) {
                    var products = block.model.get('products'),
                        formData = form2js(this.$el.find('.invoiceProductForm')[0], '.', false),
                        invoiceProductModel = new InvoiceProductModel({
                            product: this.currentInvoiceProductTarget,
                            priceEntered: formData.priceEntered,
                            quantity: formData.quantity
                        });

                    products.push(invoiceProductModel);

                    block.removeSelectProductError();
                    block.removeProductError();

                    block.validationRequest = block.model.validateProducts();

                    block.validationRequest.fail(function(res) {
                        products.remove(invoiceProductModel);

                        var errors = res.responseJSON;

                        if (errors) {
                            _.forEach(errors.errors.children.products.children, function(error) {
                                _.find(error.children, function(field) {
                                    if (field.errors) {
                                        block.showProductError(error);
                                        return true;
                                    }
                                })
                            });
                        }
                    }).done(function() {
                        block.invoiceProductFormClear();
                        block.renderInvoiceProductsTable();
                    });
                } else {
                    block.showSelectProductError();
                }
            },
            'click .delInvoiceProduct': function(event) {
                var block = this,
                    modelCid = $(event.target).attr('model-cid'),
                    products = block.model.get('products');

                products.remove(modelCid);

                block.renderInvoiceProductsTable();
            }
        },
        blocks: {
            autocomplete: function() {
                var block = this,
                    engine = new Bloodhound({
                    remote: InvoiceProductModel.baseApiUrl + '/products/search?properties[]=name&properties[]=sku&query=%QUERY',
                    ajax: {
                        dataType: 'json',
                        headers: {
                            Authorization: 'Bearer ' + cookies.get('token')
                        }
                    },
                    datumTokenizer: function(d) {
                        return Bloodhound.tokenizers.whitespace(d.val);
                    },
                    queryTokenizer: Bloodhound.tokenizers.whitespace
                });

                engine.initialize();

                $('.autocomplete').typeahead({
                        highlight: true,
                        minLength: 3
                    },
                    {
                        source: engine.ttAdapter(),
                        displayKey: 'name',
                        templates: {
                            suggestion: require('ejs!blocks/form/form_invoice/autocomplete/suggestion.ejs')
                        }
                    });

                return {
                    remove: function() {
                        $('.autocomplete').typeahead('destroy').remove();
                    }
                }
            },
            select_suppliers: function() {
                var block = this;

                return new Select_suppliers({
                    collections: _.pick(block.collections, 'suppliers')
                })
            }
        },
        initialize: function() {
            var block = this;

            Form.prototype.initialize.apply(block, arguments);

//            block.model.get('products').on('reset', function() {
//                block.finishEdit();
//            });

//            block.model.on('change:sumTotal', function() {
//                block.renderTotalSum();
//            });

            block.initBlocks();
            block.renderInvoiceProductsTable();
        },
        renderInvoiceProductsTable: function() {
            var form = this,
                table = form.$el.find('.invoiceProducts');

            table.html(invoiceProductTable({invoiceProducts: form.model.get('products')}))
        },
        invoiceProductFormClear: function() {
            this.$el.find('.invoiceProductForm input').val('');
            this.currentInvoiceProductTarget = null;
        },
        showProductError: function(error) {
            var block = this,
                errorString = '',
                tr = block.el.querySelector('.invoiceProductForm');

            _.forEach(error.children, function(field, key) {
                if (field.errors) {
                    errorString += field.errors.join(', ');
                    $(block.el.querySelector('.invoiceProductForm input[name=' + key + ']')).parent().addClass('state-error');
                }
            });
        },
        removeProductError: function() {
            var block = this;

            _.forEach(block.el.querySelectorAll('.invoiceProductForm input'), function(input) {
                input.classList.remove('inputText_error');
                $(input).parent().removeClass('state-error');
            });
        },
        showSelectProductError: function() {
            this.$el.find('input[name=product]').parents('.input-group').append('<em for="product" class="invalid">Выберите товар из списка</em>');
            this.$el.find('input[name=product]').parents('.input-group').addClass('state-error')
        },
        removeSelectProductError: function() {
            this.$el.find('em.invalid').remove();
            this.$el.find('input[name=product]').parents('.input-group').removeClass('state-error');
        }
    });
});