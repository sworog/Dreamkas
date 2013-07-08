define(function(require) {
        //requirements
        var Tooltip_editMenu = require('blocks/tooltip/tooltip_menu/tooltip_menu'),
            Tooltip_catalogGroupForm = require('blocks/tooltip/tooltip_catalogGroupForm/tooltip_catalogGroupForm');

        return Tooltip_editMenu.extend({
            classModel: null,
            events: {
                'click .tooltip__editLink': function(e) {
                    e.preventDefault();
                    var block = this,
                        $target = $(e.target);

                    block.hide();

                    block.tooltip_catalogGroupForm.show();
                },
                'click .tooltip__removeLink': function(e) {
                    e.preventDefault();
                    var block = this;

                    if (block.classModel.get('groups') && block.classModel.get('groups').length) {
                        alert('Необходимо удалить все группы из класса');
                    } else {
                        block.classModel.destroy();
                    }

                    block.hide();
                }
            },
            initialize: function() {
                var block = this;

                Tooltip_editMenu.prototype.initialize.call(this);

                block.tooltip_catalogGroupForm = new Tooltip_catalogGroupForm({
                    $trigger: block.$trigger,
                    classModel: block.classModel
                });
            }
        });
    }
);