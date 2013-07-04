define(function(require) {
    //requirements
    var Form = require('kit/blocks/form/form'),
        CatalogClassModel = require('models/catalogClass');

    return Form.extend({
        blockName: 'form_catalogClass',
        model: new CatalogClassModel(),
        isAddForm: true,
        collection: null,
        templates: {
            index: require('tpl!./templates/index.html')
        },
        initialize: function(){
            var block = this;

            Form.prototype.initialize.call(block);

            if (block.model.id){
                block.isAddForm = false;
            }
        },
        submitSuccess: function(){
            var block = this;

            Form.prototype.submitSuccess.call(block);

            if (block.isAddForm){
                block.model = new CatalogClassModel();
                block.clear();
                block.$el.find('[name="name"]').focus();
            }
        }
    });
});