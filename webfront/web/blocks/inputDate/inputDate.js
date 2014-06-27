define(function(require) {
        //requirements
        var Block = require('kit/block'),
            formatDate = require('kit/formatDate'),
            moment = require('moment');

        return Block.extend({
            dateFormat: 'DD.MM.YYYY',
            date: null,
            blocks: {
                tooltip_datepicker: function(){
                    var block = this,
                        Tooltip_datepicker = require('blocks/tooltip/tooltip_datepicker/tooltip_datepicker');

                    var tooltip_datepicker = new Tooltip_datepicker({
                        target: block.el
                    });

                    tooltip_datepicker.on('selectdate', function(date){
                        block.el.dataset.date = date;
                        block.el.value = moment(date).format(block.dateFormat);
                    });

                    return tooltip_datepicker;
                }
            },
            events: {
                'focus': function(e) {
                    var block = this;

                    block.showDatepicker({
                        date: +block.el.dataset.date
                    });
                },
                'change': function(e){
                    var block = this,
                        date = moment(block.el.value, block.dateFormat).valueOf();

                    block.el.dataset.date = date > 0 ? moment(block.el.value || null, block.dateFormat).valueOf() : '';
                }
            },
            showDatepicker: function(opt) {
                var block = this;

                block.blocks.tooltip_datepicker.show(opt);
            }
        });
    }
);