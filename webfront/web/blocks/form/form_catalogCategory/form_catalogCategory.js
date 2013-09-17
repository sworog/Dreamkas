define(function(require) {
    //requirements
    var Form = require('kit/blocks/form/form'),
        CatalogCategoryModel = require('models/catalogCategory');

    return Form.extend({
        __name__: 'form_catalogCategory',
        model: new CatalogCategoryModel(),
        collection: null,
        isAddForm: true,
        templates: {
            index: require('tpl!blocks/form/form_catalogCategory/templates/index.html')
        },
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
                block.model = new CatalogCategoryModel({
                    group: block.model.get('group')
                });
                block.clear();
                block.$el.find('[name="name"]').focus();
            }
        }
    });
});