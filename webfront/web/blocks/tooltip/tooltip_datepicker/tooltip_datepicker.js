define(function(require, exports, module) {
    //requirements
    var Tooltip = require('blocks/tooltip/tooltip');

    return Tooltip.extend({
        date: null,
        template: require('ejs!./template.ejs'),
        events: {
            'click .tooltip_datepicker__nowLink': function(e){
                var block = this;

                block.blocks.datepicker.selectNow();
                block.hide();
            }
        },
        blocks: {
            datepicker: function() {
                var block = this,
                    Datepicker = require('blocks/datepicker/datepicker');

                var datepicker = new Datepicker({
                    visibleDate: block.date,
                    selectedDate: block.date,
                    el: block.el.querySelector('.datepicker')
                });

                datepicker.on('selectdate', function(date){
                    block.trigger('selectdate', date);
                });

                datepicker.render();

                return datepicker;
            }
        }
    });
});