define(
    [
        '/kit/block.js',
        './tpl/tpl.js'
    ],
    function(Block, tpl) {
        var Calendar = Block.extend({
            defaults: {
                visibleDate: moment().valueOf(),
                selectedDate: null,
                template: 'month',
                noTime: false,
                dateList: []
            },
            tpl: tpl,
            initialize: function() {
                var block = this;

                if (!block.dateList.length) {
                    block._generateDateList();
                }

                block.$el.toggleClass('calendar_noTime', block.noTime);

                block.render();

                block.$dateList = block.$el.find('.calendar__dateList');
                block.$header = block.$el.find('.calendar__header');
            },
            events: {
                'click .calendar__setNowLink': function(e){
                    e.preventDefault();

                    var block = this;

                    block.set('selectedDate', moment().valueOf());
                },
                'click .calendar__nextMonthLink': function(e) {
                    e.preventDefault();

                    var block = this;

                    block.showNextMonth();
                },
                'click .calendar__prevMonthLink': function(e) {
                    e.preventDefault();

                    var block = this;

                    block.showPrevMonth();
                },
                'click .calendar__showNowLink': function(e) {
                    e.preventDefault();

                    var block = this;

                    block.showNow();
                },
                'click .calendar__dateItem': function(e) {
                    e.preventDefault();

                    var block = this,
                        selectedMoment = moment($(e.target).data('calendar_date')),
                        renderTimeControls = true;

                    if (!block.$el.find('.calendar__timeControls .inputText:disabled').length){
                        renderTimeControls = false;
                        block.$el.find('.calendar__timeControls .inputText').each(function(){
                            selectedMoment[$(this).attr('name')]($(this).val());
                        });
                    }

                    block.set('selectedDate', selectedMoment.valueOf(), {
                        renderTimeControls: renderTimeControls
                    });
                },
                'change .calendar__timeControls [name]': function(e) {
                    e.preventDefault();

                    var block = this;

                    block.set('selectedDate', moment(block.selectedDate)[$(e.target).attr('name')]($(e.target).val()).valueOf(), {
                        renderTimeControls: false
                    });
                }
            },
            'set:noTime': function(val){
                var block = this;

                block.$el.toggleClass('calendar_noTime', val);
            },
            'set:lang': function(val){
                var block = this;

                block.moment.lang(val);
                block.render();
            },
            'set:visibleDate': function(value) {
                var block = this;

                block.visibleDate = value;

                block
                    ._generateDateList()
                    ._renderHeader()
                    ._renderDateList();
            },
            'set:selectedDate': function(value, extra) {
                var block = this;

                if (!value){
                    block._clearSelectedDate();
                    return;
                }

                extra = _.extend({
                    renderTimeControls: true
                }, extra);

                block.$el.find('.calendar__timeControls .inputText').removeAttr('disabled');
                block.$el.find('.calendar__dateItem_selected').removeClass('calendar__dateItem_selected');
                block.$el.find('[data-calendar_date="' + moment(value).startOf('day').valueOf() + '"]').addClass('calendar__dateItem_selected');

                if (extra.renderTimeControls){
                    block.$el.find('.calendar__timeControls [name="hours"]').val(moment(value).format('HH'));
                    block.$el.find('.calendar__timeControls [name="minutes"]').val(moment(value).format('mm'));
                    block.$el.find('.calendar__timeControls [name="seconds"]').val(moment(value).format('ss'));
                }
            },
            showNextMonth: function() {
                var block = this;

                block.set('visibleDate', moment(block.visibleDate).add('months', 1).valueOf());
            },
            showPrevMonth: function() {
                var block = this;

                block.set('visibleDate', moment(block.visibleDate).subtract('months', 1).valueOf());
            },
            showNow: function() {
                var block = this;

                block.set('visibleDate', moment().valueOf());
            },
            _clearSelectedDate: function(){
                var block = this;

                block.$el.find('.calendar__timeControls .inputText').val('').attr('disabled', true);
                block.$el.find('.calendar__dateItem_selected').removeClass('calendar__dateItem_selected');
            },
            _generateDateList: function() {
                var block = this,
                    visibleMoment = moment(block.visibleDate),
                    startMoment = moment(block.visibleDate).startOf('month').day(1),
                    endMoment = moment(block.visibleDate).endOf('month').day(7),
                    diff = endMoment.diff(startMoment, 'days'),
                    itemMoment;

                block.dateList = [];

                for (var i = 0; i <= diff; i++) {
                    itemMoment = moment(startMoment).add('days', i);

                    block.dateList.push({
                        moment: itemMoment,
                        isOtherMonth: !visibleMoment.isSame(itemMoment, 'month'),
                        isNow: moment().isSame(itemMoment, 'day'),
                        isSelected: block.selectedDate ? moment(block.selectedDate).isSame(itemMoment, 'day') : false
                    });
                }

                return block;
            },
            _renderDateList: function() {
                var block = this;

                block.$dateList.html(block.tpl.dateList({
                    block: block
                }));

                return block;
            },
            _renderHeader: function() {
                var block = this;

                block.$header.html(block.tpl.header({
                    block: block
                }));

                return block;
            }
        });

        //jquery plugin

        $.fn.lh_calendar = function(opt){
            return this.each(function(){
                new Calendar(_.extend({
                    el: this
                }, opt));
            });
        };

        return Calendar;
    }
);