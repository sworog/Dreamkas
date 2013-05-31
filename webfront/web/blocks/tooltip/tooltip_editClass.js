define(
    [
        '/kit/tooltip/tooltip.js',
        '/kit/form/form.js',
        '/models/catalogClass.js',
        'tpl!./templates/tooltip_editClass.html'
    ],
    function(Tooltip, Form, CatalogClassModel, tooltip_editClassTpl) {
        return Tooltip.extend({
            classModel: null,
            addClass: 'tooltip_editClass',
            templates: {
                content: tooltip_editClassTpl
            },

            initialize: function(){
                var block = this;

                block.classModel = block.classModel || new CatalogClassModel();

                Tooltip.prototype.initialize.call(this);

                block.form = new Form({
                    el: block.el.getElementsByClassName('form'),
                    model: block.classModel
                });

                block.listenTo(block.form, {
                    successSubmit: function(){
                        block.hide();
                    }
                });
            },
            show: function(){
                var block = this;

                Tooltip.prototype.show.apply(this, arguments);

                block.form.$el.find('[type="text"]').eq(0).focus();
            }
        });
    }
);