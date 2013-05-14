define(
    [
        '/kit/block.js',
        '/kit/datePicker/datePicker.inst.js'
    ],
    function(Block, datePicker) {
        return Block.extend({
            defaults: {
                date: null
            },
            initialize: function() {
                var block = this;
            },
            'set:date': function(date) {
                var block = this;

                block.$el.val(date ? moment(date).format('DD.MM.YYYY HH:mm') : '');
            },
            events: {
                'focus': function(e) {
                    var block = this;

                    block.showDatePicker();
                }
            },
            showDatePicker: function() {
                var block = this;

                datePicker.inputDate = block;

                datePicker.show({
                    $trigger: block.$el
                });

                datePicker.calendar.set({
                    selectedDate: null,
                    visibleDate: moment().valueOf()
                });
            }
        });
    }
);