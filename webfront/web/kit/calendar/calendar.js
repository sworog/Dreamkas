define(
    [
        '/kit/block.js',
        './tpl/tpl.js'
    ],
    function(Block, tpl) {
        return Block.extend({
            defaults: {
                visibleDate: moment().valueOf(),
                selectedDate: null,
                template: 'month',
                dateList: []
            },
            tpl: tpl,
            initialize: function() {
                var block = this;

                if (!block.dateList.length) {
                    block._generateDateList();
                }

                block.render();

                block.$dateList = block.$el.find('.calendar__dateList');
                block.$header = block.$el.find('.calendar__header');

                block.set({d: {e: {f: 'test'}}});

                block.listenTo(block, {
                    'set:visibleDate': function() {
                        block
                            ._generateDateList()
                            ._renderHeader()
                            ._renderDateList();
                    }
                });
            },
            events: {
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
                'click .calendar__nowLink': function(e) {
                    e.preventDefault();

                    var block = this;

                    block.showNow();
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
            _generateDateList: function() {
                var block = this,
                    visibleMoment = moment(block.visibleDate),
                    startMoment = moment(block.visibleDate).startOf('month').startOf('week'),
                    endMoment = moment(block.visibleDate).endOf('month').endOf('week'),
                    diff = endMoment.diff(startMoment, 'days'),
                    itemMoment;

                block.dateList = [];

                for (var i = 0; i <= diff; i++) {
                    itemMoment = moment(startMoment).add('days', i);

                    block.dateList.push({
                        moment: itemMoment,
                        isOtherMonth: !visibleMoment.isSame(itemMoment, 'month'),
                        isNow: moment().isSame(itemMoment, 'day')
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
    }
);