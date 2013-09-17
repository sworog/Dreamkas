define(function(require) {
    //requirements
    var Form = require('kit/blocks/form/form'),
        CatalogGroupModel = require('models/catalogGroup');

    return Form.extend({
        __name__: 'form_catalogGroup',
        model: new CatalogGroupModel(),
        isAddForm: true,
        collection: null,
        templates: {
            index: require('tpl!blocks/form/form_catalogGroup/templates/index.html')
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
                block.model = new CatalogGroupModel();
                block.clear();
                block.$el.find('[name="name"]').focus();
            }
        }
    });
});