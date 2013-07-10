define(function(require) {
    //requirements
    var Form = require('kit/blocks/form/form'),
        CatalogCategoryModel = require('models/catalogCategory');

    return Form.extend({
        blockName: 'form_catalogCategory',
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

            Form.prototype.initialize.call(block);
        },
        submitSuccess: function(){
            var block = this;

            Form.prototype.submitSuccess.call(block);

            if (block.isAddForm){
                block.model = new CatalogCategoryModel({}, {
                    parentGroupModel: block.model.parentGroupModel
                });
                block.clear();
                block.$el.find('[name="name"]').focus();
            }
        }
    });
});