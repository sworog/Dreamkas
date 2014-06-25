define(function(require) {
        //requirements
        var Block = require('kit/core/block.deprecated'),
            moment = require('moment');

        return Block.extend({
            __name__: 'datepicker',
            className: 'datepicker',
            visibleDate: moment().valueOf(),
            selectedDate: null,
            noTime: false,
            dateList: [],
            template: require('ejs!blocks/datepicker/templates/index.html'),
            templates: {
                index: require('ejs!blocks/datepicker/templates/index.html'),
                controls: require('ejs!blocks/datepicker/templates/controls.html'),
                dateList: require('ejs!blocks/datepicker/templates/dateList.html'),
                header: require('ejs!blocks/datepicker/templates/header.html')
            },

            initialize: function() {
                var block = this;

                if (!block.dateList.length) {
                    block._generateDateList();
                }

                block.$el.toggleClass('datepicker_noTime', block.noTime);

                block.render();
            },

            events: {
                'click .datepicker__saveLink': function(e){
                    e.preventDefault();

                    var block = this;

                    block.trigger('save');
                },
                'click .datepicker__closeLink': function(e){
                    e.preventDefault();

                    var block = this;

                    block.trigger('close');
                },
                'click .datepicker__setNowLink': function(e){
                    e.preventDefault();

                    var block = this;

                    block.set('selectedDate', moment().valueOf());
                },
                'click .datepicker__nextMonthLink': function(e) {
                    e.preventDefault();

                    var block = this;

                    block.showNextMonth();
                },
                'click .datepicker__prevMonthLink': function(e) {
                    e.preventDefault();

                    var block = this;

                    block.showPrevMonth();
                },
                'click .datepicker__showNowLink': function(e) {
                    e.preventDefault();

                    var block = this;

                    block.showNow();
                },
                'click .datepicker__dateItem': function(e) {
                    e.preventDefault();

                    var block = this,
                        selectedMoment = moment(+$(e.target).data('date'));

                    if (!block.noTime){
                        block.$el.find('.datepicker__timeControls .inputText').each(function(){
                            selectedMoment[$(this).attr('name')]($(this).val() || 0);
                        });
                    }

                    block.set('selectedDate', selectedMoment.valueOf());
                },
                'change .datepicker__timeControls .inputText': function(e) {
                    e.preventDefault();

                    var block = this,
                        date = moment(block.selectedDate)[$(e.target).attr('name')]($(e.target).val()).valueOf();

                    block.set('selectedDate', date, {
                        renderTimeControls: false
                    });
                }
            },
            'set:noTime': function(val){
                var block = this;

                block.$el.toggleClass('datepicker_noTime', val);
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
                    renderTimeControls: !block.noTime
                }, extra);

                block.$el.find('.datepicker__timeControls .inputText').removeAttr('disabled');
                block.$el.find('.datepicker__dateItem_selected').removeClass('datepicker__dateItem_selected');
                block.$el.find('.datepicker__dateItem[data-date="' + moment(value).startOf('day').valueOf() + '"]').addClass('datepicker__dateItem_selected');

                if (extra.renderTimeControls){
                    block.$el.find('.datepicker__timeControls [name="hours"]').val(moment(value).format('HH'));
                    block.$el.find('.datepicker__timeControls [name="minutes"]').val(moment(value).format('mm'));
                    block.$el.find('.datepicker__timeControls [name="seconds"]').val(moment(value).format('ss'));
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

                block.$el.find('.datepicker__timeControls .inputText').val('').attr('disabled', true);
                block.$el.find('.datepicker__dateItem_selected').removeClass('datepicker__dateItem_selected');
            },
            _generateDateList: function() {
                var block = this,
                    visibleMoment = moment(block.visibleDate),
                    startMoment = moment(block.visibleDate).startOf('month').startOf('week').day(1),
                    endMoment = moment(block.visibleDate).endOf('month').endOf('week').day(7),
                    diff,
                    itemMoment;

                if (endMoment.date() == 7){
                    endMoment.day(-7);
                }

                if (startMoment.date() == 2){
                    startMoment.day(-6);
                }

                diff = endMoment.diff(startMoment, 'days');
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

                block.$dateList.html(block.templates.dateList(block));

                return block;
            },
            _renderHeader: function() {
                var block = this;

                block.$header.html(block.templates.header(block));

                return block;
            }
        });
    }
);