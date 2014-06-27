define(function(require) {
        //requirements
        var Block = require('kit/block'),
            $ = require('jquery'),
            moment = require('moment');

        return Block.extend({
            visibleDate: null,
            selectedDate: null,

            template: require('ejs!./template.ejs'),

            dateList: function() {
                var block = this,
                    dateList = [],
                    visibleMoment = moment(block.visibleDate || moment().valueOf()),
                    startMoment = moment(visibleMoment).startOf('month').startOf('week'),
                    endMoment = moment(visibleMoment).endOf('month').endOf('week'),
                    diff,
                    itemMoment;

                diff = endMoment.diff(startMoment, 'days');

                for (var i = 0; i <= diff; i++) {
                    itemMoment = moment(startMoment).add('days', i);

                    dateList.push({
                        moment: itemMoment,
                        isOtherMonth: !visibleMoment.isSame(itemMoment, 'month'),
                        isNow: moment().isSame(itemMoment, 'day'),
                        isSelected: block.selectedDate ? moment(block.selectedDate).isSame(itemMoment, 'day') : false
                    });
                }

                return dateList;
            },
            
            events: {
                'click .datepicker__setNowLink': function(e) {
                    e.preventDefault();

                    var block = this;

                    block.selectNow();
                },
                'click .datepicker__nextMonthLink': function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    var block = this;

                    block.showNextMonth();
                },
                'click .datepicker__prevMonthLink': function(e) {
                    e.preventDefault();
                    e.stopPropagation();

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
                        selectedMoment = moment(+e.target.dataset.date);

                    block.selectDate(selectedMoment.valueOf());
                }
            },
            showNextMonth: function() {
                var block = this;

                block.showDate(moment(block.visibleDate).add('months', 1).valueOf());
            },
            showPrevMonth: function() {
                var block = this;

                block.showDate(moment(block.visibleDate).subtract('months', 1).valueOf());
            },
            showNow: function() {
                var block = this;

                block.showDate(moment().valueOf());
            },
            showDate: function(date){
                var block = this;
                
                block.visibleDate = date;

                this.render();

                block.trigger('showdate', date);
            },
            selectDate: function(date){
                var block = this;
                
                block.selectedDate = date;

                if (!date) {
                    block._clearSelectedDate();
                    return;
                }

                $(block.el).find('.datepicker__dateItem_selected').removeClass('datepicker__dateItem_selected');
                $(block.el).find('.datepicker__dateItem[data-date="' + moment(date).startOf('day').valueOf() + '"]').addClass('datepicker__dateItem_selected');

                block.trigger('selectdate', date);
            },
            selectNow: function(){
                var block = this;

                block.selectDate(moment().valueOf());
            },
            _clearSelectedDate: function() {
                var block = this;

                block.el.querySelector('.datepicker__dateItem_selected').classList.remove('datepicker__dateItem_selected');
            }
        });
    }
);