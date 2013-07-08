define(function(require) {
        //requirements
        var Tooltip_menu = require('blocks/tooltip/tooltip_menu/tooltip_menu'),
            Tooltip_catalogGroupForm = require('blocks/tooltip/tooltip_catalogGroupForm/tooltip_catalogGroupForm');

        return Tooltip_menu.extend({
            catalogGroupModel: null,
            blockName: 'tooltip_catalogGroupMenu',
            events: {
                'click .tooltip__editLink': function(e) {
                    e.preventDefault();
                    var block = this,
                        $target = $(e.target);

                    block.blocks.tooltip_catalogGroupForm.show({
                        catalogGroupModel: block.catalogGroupModel,
                        $trigger: $target,
                        align: function(){
                            var tooltip = this;

                            tooltip.$el
                                .css({
                                    top: tooltip.$trigger.offset().top - 15,
                                    left: tooltip.$trigger.offset().left
                                })
                        }
                    });

                    block.hide();
                },
                'click .tooltip__removeLink': function(e) {
                    e.preventDefault();
                    var block = this;

                    if (block.catalogGroupModel.get('groups') && block.classModel.get('groups').length) {
                        alert('Необходимо удалить все группы из класса');
                    } else {
                        block.classModel.destroy();
                    }

                    block.hide();
                }
            },
            initialize: function() {
                var block = this;

                Tooltip_menu.prototype.initialize.call(this);

                block.blocks.tooltip_catalogGroupForm = block.blocks.tooltip_catalogGroupForm || new Tooltip_catalogGroupForm({
                    $trigger: block.$trigger,
                    classModel: block.classModel
                });
            }
        });
    }
);