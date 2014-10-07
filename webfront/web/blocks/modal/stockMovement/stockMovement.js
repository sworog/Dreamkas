define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        template: require('ejs!./template.ejs'),
        itemId: null,
		formId: null,
		Form: null,
		Form_products: null,
		Model: null,
        model: function(){
            var Model = this.Model;

            return PAGE.collections.stockMovements.get(this.itemId) || new Model;
        },
        events: {
            'click .modal_stockMovement__removeLink': function(e){
                var block = this;

                e.target.classList.add('loading');

                block.model.destroy().then(function() {
                    e.target.classList.remove('loading');
                    block.hide();
                });
            }
        },
        blocks: {
            form: function(){
                var Form = this.Form;

                return new Form({
                    model: this.model
                });
            },
            form_products: function(){
                var Form_products = this.Form_products;

                return new Form_products({
                    collection: this.model.collections.products
                });
            }
        }
    });
});