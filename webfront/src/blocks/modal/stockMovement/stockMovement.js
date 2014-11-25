define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        template: require('ejs!./template.ejs'),
        warningForDeletedSupplier: 'Операция для удаленного поставщика.',
        warningForDeletedStore: 'Операция для удаленного магазина.',
        itemId: null,
		Form: null,
		Form_products: null,
		Model: null,
        deleted: false,
        deletedTitle: 'Удаление прошло успешно',
        removeButtonText: 'Удалить операцию',
        isStoreDeleted: false,
        isSupplierDeleted: false,
        isFormDisabled: false,
        initialize: function(){

            Modal.prototype.initialize.apply(this, arguments);

            var block = this,
                supplier = block.model.get('supplier'),
                store = block.model.get('store');

            block.isSupplierDeleted = false;
            block.isStoreDeleted = false;
            block.isFormDisabled = false;

            if (supplier && supplier.deletedAt) {
                block.isSupplierDeleted = true;
            }

            if (store && store.deletedAt) {
                block.isStoreDeleted = true;
            }

            if (block.isStoreDeleted || block.isSupplierDeleted){
                block.isFormDisabled = true;
            }

        },
        partials: {
            deleted: require('ejs!./deleted.ejs')
        },
        model: function(){
            var Model = this.Model;

            return PAGE.collections.stockMovements.get(this.itemId) || new Model;
        },
        events: {
            'click .form_stockMovement__removeLink': function(e){
                var block = this;

                e.target.classList.add('loading');

                block.model.destroy().then(function() {
                    e.target.classList.remove('loading');
                });
            }
        },
        blocks: {
            form: function(opt){
                var Form = this.Form;

                return new Form(_.extend({
                    model: this.model
                }, opt));
            },
            form_products: function(){
                var Form_products = this.Form_products;

                return new Form_products({
                    collection: this.model.collections.products
                });
            },
            form_store: require('blocks/form/store/store'),
            removeButton: function(options){

                var block = this,
                    RemoveButton = require('blocks/removeButton/removeButton');

                return new RemoveButton(_.extend({
                    model: block.model
                }, options));
            }
        },
        close: function(options) {

            if (options.submitSuccess) {
                this.model.collections.products.applyChanges();
            } else {
                this.model.collections.products.restore();
            }

            return Modal.prototype.close.call(this, arguments);
        }
    });
});