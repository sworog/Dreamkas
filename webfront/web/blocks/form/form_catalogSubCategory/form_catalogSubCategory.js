define(function(require) {
    //requirements
    var Form = require('kit/blocks/form/form'),
        CatalogSubcategoryModel = require('models/catalogSubcategory');

    return Form.extend({
        __name__: 'form_catalogCategory',
        model: new CatalogSubcategoryModel(),
        collection: null,
        isAddForm: true,
        template: require('tpl!blocks/form/form_catalogSubcategory/templates/index.html'),
        initialize: function(){
            var block = this;

            if (block.model.id){
                block.isAddForm = false;
            }
        },
        submitSuccess: function(){
            var block = this;

            Form.prototype.submitSuccess.apply(block, arguments);

            if (block.isAddForm){
                block.model = new CatalogSubcategoryModel({
                    category: block.model.get('category'),
                    group: block.model.get('group')
                });
                block.clear();
                block.$el.find('[name="name"]').focus();
            }
        }
    });
});