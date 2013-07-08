define(function(require) {
        //requirements
        var Tooltip = require('kit/blocks/tooltip/tooltip'),
            Form = require('blocks/form/form'),
            CatalogGroupModel = require('models/catalogGroup');

        return Tooltip.extend({
            groupModel: null,
            classModel: null,
            addClass: 'tooltip_editGroup',
            templates: {
                content: require('tpl!blocks/tooltip/tooltip_editGroup/templates/index.html')
            },

            initialize: function() {
                var block = this,
                    group = _.find(block.classModel.get('groups'), function(group) {
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
                    change: function(model) {
                        block.classModel.set('groups', _.map(block.classModel.get('groups'), function(group) {
                            if (group.id === model.id) {
                                return model.toJSON();
                            }

                            return group;
                        }));
                    }
                });

                block.listenTo(block.form, {
                    successSubmit: function() {
                        block.hide();
                    }
                });
            },
            show: function() {
                var block = this;

                Tooltip.prototype.show.apply(this, arguments);

                block.form.$el.find('[type="text"]').eq(0).focus();
            }
        });
    }
);