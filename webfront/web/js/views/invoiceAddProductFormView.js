var InvoiceAddProductFormView = Backbone.View.extend({
    template: Mustache.compile($("#invoiceAddProductForm").html()),

    events: {
        'click [lh_button="commit"]': 'finishAdd',
        'click a.addMoreProduct': 'addProduct'
    },

    initialize: function() {
        this.model.bind('error', this.renderErrors, this);
    },

    render: function() {
        this.$el.html(this.template());

        this.$el.find("[lh_product_autocomplete='name']").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: baseApiUrl + "/api/1/products/name/search.json",
                    dataType: "json",
                    data: {
                        query: request.term
                    },
                    success: function(data) {
                        response($.map(data, function( item ) {
                            return {
                                label: item.name,
                                product: item
                            }
                        }));
                    }
                })
            },
            minLength: 2,
            select: function(event, ui) {
                $(this).parents("form").find("[lh_product_autocomplete='sku']").val(ui.item.product.sku);
                $(this).parents("form").find("[lh_product_autocomplete='barcode']").val(ui.item.product.barcode);
            }
        });

        return this;
    },

    addProduct: function(event) {
        event.preventDefault();

        var data = Backbone.Syphon.serialize(this);
        this.model.set(data);
        this.model.save({}, {
            success: this.addProductAndClearForm,
            error: function(model, response) {
                model.parseErrors($.parseJSON(response.responseText));
            }
        });
    },

    finishAdd: function() {

    },

    renderErrors: function() {
        this.$el.find("input, textarea, select").parent("span").removeAttr("lh_field_error");
        for(var field in this.model.errors) {
            var fieldErrors = this.model.errors[field].join(', ');
            this.$el.find("[name='" + field + "']").parent("span").attr("lh_field_error", fieldErrors);
        }
    }
});