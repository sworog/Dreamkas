define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        template: require('ejs!./template.ejs'),
        itemId: null,
		Form: null,
		Form_products: null,
		Model: null,
        deleted: false,
        deletedTitle: 'Удаление прошло успешно',
        partials: {
            deleted: require('ejs!./deleted.ejs')
        },
        initialize: function(data){

            data = data || {};

            if (typeof data.deleted === 'undefined'){
                this.deleted = false;
            }

            return Modal.prototype.initialize.apply(this, arguments);
        },
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
                    block.show({
                        deleted: true
                    });
                });
            }
        },
        blocks: {
            form: function(opt){
                var Form = this.Form;

                return new Form(_.extend({
                    model: this.model,
                    modalId: this.id
                }, opt));
            },
            form_products: function(){
                var Form_products = this.Form_products;

                return new Form_products({
                    modalId: this.id,
                    collection: this.model.collections.products
                });
            },
            form_store: require('blocks/form/store/store')
        },
        hide: function(options) {
            options = options || {};

            if (options.submitSuccess) {
                this.model.collections.products.applyChanges();
            } else {
                this.model.collections.products.restore();
            }

            return Modal.prototype.hide.call(this, arguments);
        }
    });
});