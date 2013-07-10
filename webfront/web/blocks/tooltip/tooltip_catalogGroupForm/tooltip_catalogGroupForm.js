define(function(require) {
        //requirements
        var Tooltip = require('kit/blocks/tooltip/tooltip'),
            Form_catalogGroup = require('blocks/form/form_catalogGroup/form_catalogGroup'),
            CatalogGroupModel = require('models/catalogGroup');

        return Tooltip.extend({
            catalogGroupModel: new CatalogGroupModel(),
            catalogGroupsCollection: null,
            isAddForm: true,
            blockName: 'tooltip_catalogGroupForm',
            templates: {
                content: require('tpl!blocks/tooltip/tooltip_catalogGroupForm/templates/content.html')
            },
            listeners: {
                form: {
                    'submit:success': function() {
                        var block = this;

                        if (!block.form.isAddForm){
                            block.hide();
                        }
                    }
                }
            },
            initialize: function() {
                var block = this;

                Tooltip.prototype.initialize.call(this);

                block.form = new Form_catalogGroup({
                    el: block.el.getElementsByClassName('form'),
                    model: block.catalogGroupModel,
                    collection: block.catalogGroupsCollection
                });

                if (block.catalogGroupModel.id){
                    block.isAddForm = false;
                }
            },
            align: function(){
                var tooltip = this;

                tooltip.$el
                    .css({
                        top: tooltip.$trigger.offset().top - (tooltip.$el.outerHeight() - tooltip.$trigger.outerHeight())/2,
                        left: tooltip.$trigger.offset().left
                    })
            },
            show: function(opt) {
                var block = this;

                Tooltip.prototype.show.apply(this, arguments);

                block.initialize();
                block.startListening();

                block.form.$el.find('[type="text"]').eq(0).focus();
            }
        });
    }
);