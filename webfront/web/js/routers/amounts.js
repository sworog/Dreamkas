var AmountsRouter = Backbone.Router.extend({
    routes: {
        "amount/list":         "amounts_list"
    },

    collections: {
        products: null
    },

    models: {
        product: null
    },

    amounts_list: function() {
        if( ! this.collections.products) {
            this.collections.products = new ProductsCollection;
        }

        var view = new AmountsList({collection: this.collections.products});
        $("[lh_application]").html(view.render().el);
    }
});
