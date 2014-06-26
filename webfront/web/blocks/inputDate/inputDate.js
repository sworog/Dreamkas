define(function(require) {
        //requirements
        var Block = require('kit/block'),
            moment = require('moment');

        require('jquery.maskedinput');

        return Block.extend({
            date: null,
            blocks: {
                tooltip_datepicker: function(){
                    var block = this,
                        Tooltip_datepicker = require('blocks/tooltip/tooltip_datepicker/tooltip_datepicker');
                }
            },
            events: {
                'focus': function(e) {
                    var block = this;

                    block.showDatePicker();
                },
                'change': function(e){
                    var block = this,
                        date = moment(block.$el.val() || null, block.dateFormat);

                    if (date){
                        block.set('date', date.valueOf(), {
                            updateInput: false
                        });
                    }
                }
            },
            showDatePicker: function() {
                var block = this;

                console.log(11);
            }
        });
    }
);