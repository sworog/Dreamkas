var ProductsRouter = Backbone.Router.extend({
    routes: {
        "products":             "products_list",
        "product/list":         "products_list",
        "product/view/:id":     "product_view"
//        "product/edit/:id":     "product_edit"
//        "product/create":       "product_create"
    },

    collections: {
        products: null
    },

    models: {
        product: null
    },

    products_list: function() {
        if(this.collections.products) {
            delete this.collections.products;
        }
        this.collections.products = new ProductsCollection;

        var view = new ProductsList({collection: this.collections.products});
        $("[lh_application]").html(view.render().el);
    },

    product_view: function(id) {
        if( ! this.models.product) {
            this.models.product = new Product({id: id});
        } else {
            this.models.product.set({id: id});
        }
        this.models.product.fetch();

        var view = new ProductView({model: this.models.product});
        $("[lh_application]").html(view.el);
    },

    product_edit: function(id) {
        if( ! this.models.product) {
            this.models.product = new Product({id: id});
        } else {
            this.models.product.set({id: id});
        }
        this.models.product.fetch();

        var view = new ProductEdit({model: this.models.product});
        $("[lh_application]").html(view.el);
    },

    product_create: function() {
        var view = new ProductCreate({model: new Product});
        $("[lh_application]").html(view.render().el);
    }
});
