define(
    [
        './tooltip_editMenu.js'
    ],
    function(tooltip_editMenu) {
        return tooltip_editMenu.extend({
            events: {
                'click .catalogEditTooltip__editLink': function(e){
                    e.preventDefault();
                    var block = this;


                }
            }
        });
    }
);