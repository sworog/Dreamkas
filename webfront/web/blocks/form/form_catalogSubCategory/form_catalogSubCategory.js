define(function(require) {
    //requirements
    var Form = require('kit/blocks/form/form'),
        CatalogSubCategoryModel = require('models/catalogSubCategory');

    return Form.extend({
        __name__: 'form_catalogCategory',
        model: new CatalogSubCategoryModel(),
        collection: null,
        isAddForm: true,
        templates: {
            index: require('tpl!blocks/form/form_catalogSubCategory/templates/index.html')
        },
        initialize: function(){
            var block = this;

            if (block.model.id){
                block.isAddForm = false;
            }

            Form.prototype.initialize.call(block);
        },
        onSubmitSuccess: function(){
            var block = this;

            Form.prototype.onSubmitSuccess.apply(block, arguments);

            if (block.isAddForm){
                block.model = new CatalogSubCategoryModel({
                    category: block.model.get('category'),
                    group: block.model.get('group')
                });
                block.clear();
                block.$el.find('[name="name"]').focus();
            }
        }
    });
});