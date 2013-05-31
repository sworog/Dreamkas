define(
    [
        './tooltip_editMenu.js',
        './tooltip_editClass.js'
    ],
    function(Tooltip_editMenu, Tooltip_editClass) {
        return Tooltip_editMenu.extend({
            classModel: null,
            events: {
                'click .tooltip__editLink': function(e){
                    e.preventDefault();
                    var block = this,
                        $el = $(e.target);

                    block.hide();

                    block.tooltip_editClass.show();
                },
                'click .tooltip__removeLink': function(e){
                    e.preventDefault();
                    var block = this;

                    if (block.classModel.get('groups') && block.classModel.get('groups').length){
                        alert('Необходимо удалить все группы из класса');
                    } else {
                        block.classModel.destroy();
                    }

                    block.hide();
                }
            },
            initialize: function(){
                var block = this;

                Tooltip_editMenu.prototype.initialize.call(this);

                block.tooltip_editClass = new Tooltip_editClass({
                    $trigger: block.$trigger,
                    classModel: block.classModel
                });
            }
        });
    }
);