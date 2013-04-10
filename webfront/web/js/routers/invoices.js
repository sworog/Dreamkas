var InvoicesRouter = Backbone.Router.extend({
    routes: {
        "invoices":             "invoices_list",
        "invoice/list":         "invoices_list",
        "invoice/view/:id":     "invoice_view",
        "invoice/edit/:id":     "invoice_edit",
        "invoice/create":       "invoice_create",
        "invoice/products/:id": "invoice_products"
    },

    collections: {
        invoices: null
    },

    models: {
        invoice: null
    },

    invoices_list: function() {
        if( ! this.collections.invoices) {
            this.collections.invoices = new InvoicesCollection
        }

        var view = new InvoicesListView({collection: this.collections.invoices});
        $("[lh_application]").html(view.render().el);
    },

    invoice_view: function(id) {
        if( ! this.models.invoice) {
            this.models.invoice = new Invoice({id: id});
        } else {
            this.models.invoice.set({id: id});
        }
        this.models.invoice.fetch();

        var view = new InvoiceView({model: this.models.invoice});
        $("[lh_application]").html(view.el);
    },

    invoice_edit: function(id) {
        if( ! this.models.invoice) {
            this.models.invoice = new Invoice({id: id});
        } else {
            this.models.invoice.set({id: id});
        }
        this.models.invoice.fetch();

        var view = new InvoiceEdit({model: this.models.invoice});
        $("[lh_application]").html(view.el);
    },

    invoice_create: function() {
        var view = new InvoiceCreateView({model: new Invoice});
        $("[lh_application]").html(view.render().el);
    },

    invoice_products: function(id) {
        if( ! this.models.invoice) {
            this.models.invoice = new Invoice({id: id});
        } else {
            this.models.invoice.set({id: id});
        }
        this.models.invoice.fetch();

        var view = new InvoiceAddProductView({model: this.models.invoice});
        $("[lh_application]").html(view.el);
    }
});