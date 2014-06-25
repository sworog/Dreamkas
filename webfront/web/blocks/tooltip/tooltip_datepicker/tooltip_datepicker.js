define(function(require, exports, module) {
    //requirements
    var Tooltip = require('blocks/tooltip/tooltip');

    return Tooltip.extend({
        blocks: {
            datepicker: require('blocks/datepicker/datepicker')
        },
        initialize: function(){
            var block = this;

            Tooltip.prototype.initialize.apply(block, arguments);
        }
    });
});