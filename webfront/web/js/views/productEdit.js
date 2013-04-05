var ProductEdit = ProductCreate.extend({
    initialize: function() {
        this.model.bind('error', this.render, this);
        this.model.bind('sync', this.render, this);
    }
});