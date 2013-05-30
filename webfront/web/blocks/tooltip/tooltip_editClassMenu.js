define(
    [
        './tooltip_editMenu.js'
    ],
    function(tooltip_editMenu) {
        return tooltip_editMenu.extend({
            classModel: null,
            events: {
                'click .tooltip__editLink': function(e){
                    e.preventDefault();
                    var block = this;

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
            }
        });
    }
);