define(
    [
        '/kit/tooltip/tooltip.js',
        '/kit/form/form.js',
        '/models/catalogGroup.js',
        'tpl!./templates/tooltip_editGroup.html'
    ],
    function(Tooltip, Form, CatalogGroupModel, tooltip_editGroupTpl) {
        return Tooltip.extend({
            groupModel: null,
            classModel: null,
            addClass: 'tooltip_editGroup',
            templates: {
                content: tooltip_editGroupTpl
            },

            initialize: function(){
                var block = this,
                    group = _.find(block.classModel.get('groups'), function(group){
                        return group.id == block.groupId;
                    });

                block.groupModel = new CatalogGroupModel(_.extend({
                    klass: block.classModel.id
                }, group));

                Tooltip.prototype.initialize.call(this);

                block.form = new Form({
                    el: block.el.getElementsByClassName('form'),
                    model: block.groupModel
                });

                block.listenTo(block.groupModel, {
                    change: function(model){
                        block.classModel.set('groups', _.map(block.classModel.get('groups'), function(group){
                            if (group.id === model.id){
                                return model.toJSON();
                            }

                            return group;
                        }));
                    }
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