define(
    [
        '/kit/block.js',
        '/kit/calendar/calendar.js',
        '/kit/tooltip/tooltip.js',
        './tpl/tpl.js'
    ],
    function(Block, Calendar, Tooltip, tpl) {
        return Tooltip.extend({
            tpl: tpl,
            initialize: function(){
                var block = this;
                Tooltip.prototype.initialize.call(block, arguments);

                block.calendar = new Calendar({
                    el: block.el.getElementsByClassName('calendar')
                });

                block.listenTo(block.calendar, {
                    'set:selectedDate': function(date){
                        if (block.inputDate){
                            block.inputDate.set('date', date);
                        }
                    }
                });
            }
        });
    }
);