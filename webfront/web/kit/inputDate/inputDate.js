define(
    [
        '/kit/block.js',
        '/kit/datepicker/datepicker.js',
        '/kit/tooltip/tooltip.js'
    ],
    function(Block, Datepicker, Tooltip) {
        return Block.extend({
            defaults: {
                date: null,
                noTime: false
            },

            tagName: 'input',
            className: 'inputDate',

            initialize: function() {
                var block = this;

                if (block.noTime){
                    block.$el.mask('99.99.9999');
                    block.dateFormat = 'DD.MM.YYYY';
                } else {
                    block.$el.mask('99.99.9999 99:99');
                    block.dateFormat = 'DD.MM.YYYY HH:mm'
                }

                block.tooltip = new Tooltip({
                    $trigger: block.$el
                });

                block.datepicker = new Datepicker({
                    noTime: block.noTime
                });

                block.tooltip.$el.addClass('tooltip__inputDate');
                block.tooltip.$content.html(block.datepicker.$el);

                block.$el.change();

                block.listenTo(block.datepicker, {
                    'set:selectedDate': function(date){
                        block.set('date', date, {
                            updateDatepicker: false
                        });
                    },
                    save: function(){
                        block.tooltip.hide();
                    }
                });
            },
            'set:date': function(date, extra) {
                var block = this;

                extra = _.extend({
                    updateInput: true,
                    updateDatepicker: true
                }, extra);

                if (extra.updateInput){
                    block.$el.val(date ? moment(date).format(block.dateFormat) : '');
                }

                if (extra.updateDatepicker){
                    block.datepicker.set('selectedDate', date);
                }
            },
            events: {
                'focus': function(e) {
                    var block = this;

                    block.showDatePicker();
                },
                'change': function(e){
                    var block = this,
                        date = moment(block.$el.val(), block.dateFormat);

                    if (date){
                        block.set('date', date.valueOf(), {
                            updateInput: false
                        });
                    }
                }
            },
            showDatePicker: function() {
                var block = this;

                block.tooltip.show();
            }
        });
    }
);