var InvoiceAddProductFormView = Backbone.View.extend({
    template: Mustache.compile($("#invoiceAddProductForm").html()),

    events: {
        'click [lh_button="commit"]': 'finishAdd',
        'click a.addMoreProduct': 'addProduct'
    },

    initialize: function() {
        this.model.bind('error', this.renderErrors, this);
        this.model.bind('sync', this.addProductAndClearForm, this);
    },

    reinitialize: function(newModel) {
        this.model.unbind('error', this.renderErrors, this);
        this.model.unbind('sync', this.addProductAndClearForm, this);
        this.model = newModel;
        this.initialize();
    },

    render: function() {
        this.$el.html(this.template());

        this.autocompleteToInput('name');
        this.autocompleteToInput('sku');
        this.autocompleteToInput('barcode');

        return this;
    },

    addProduct: function(event) {
        event.preventDefault();

        var data = Backbone.Syphon.serialize(this);
        this.saveModel(data);
    },

    saveModel: function(data, successCallback) {
        this.model.set(data);
        this.model.save({}, {
            error: function(model, response) {
                model.parseErrors($.parseJSON(response.responseText));
            },
            success: successCallback
        });
    },

    addProductAndClearForm: function(model, response, options) {
        this.collection.add(model);
        this.invoice.fetch();
        this.clearForm();
        var newModel = new InvoiceProduct({
            invoice: this.model.get('invoice')
        });
        this.reinitialize(newModel);
    },

    clearForm: function() {
        this.$el.find("input, textarea, select").parent("span").removeAttr("lh_field_error");
        this.$el.find('form').find(':input').each(function() {
            switch(this.type) {
                case 'password':
                case 'select-multiple':
                case 'select-one':
                case 'text':
                case 'textarea':
                    $(this).val('');
                    break;
                case 'checkbox':
                case 'radio':
                    this.checked = false;
            }
        });
    },

    finishAdd: function(event) {
        event.preventDefault();
        var data = Backbone.Syphon.serialize(this);
        var isEmpty = function(data) {
            for (var item in data) {
                if ('' != data[item]) {
                    return false;
                }
            }
            return true;
        };
        var successCallback = function(){
            app.navigate('invoice/list', {trigger: true})
        }

        if (!isEmpty(data)) {
            this.saveModel(data, successCallback);
        } else {
            successCallback();
        }
    },

    renderErrors: function() {
        this.$el.find("input, textarea, select").parent("span").removeAttr("lh_field_error");
        for(var field in this.model.errors) {
            var fieldErrors = this.model.errors[field].join(', ');

            if(field == 'product') {
                this.$el.find("[lh_product_autocomplete='name']").parent("span").attr("lh_field_error", fieldErrors);
            } else {
                this.$el.find("[name='" + field + "']").parent("span").attr("lh_field_error", fieldErrors);
            }
        }
    },

    autocompleteToInput: function(name) {
        var input = this.$el.find("[lh_product_autocomplete='"+ name +"']");
        input.autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: baseApiUrl + "/api/1/products/"+ name +"/search.json",
                    dataType: "json",
                    data: {
                        query: request.term
                    },
                    success: function(data) {
                        response($.map(data, function( item ) {
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
        input.keyup(function(event){
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
    }
});