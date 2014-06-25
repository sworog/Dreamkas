define(function(require) {
        //requirements
        var Block = require('kit/core/block.deprecated'),
            moment = require('moment'),
            Datepicker = require('blocks/datepicker/datepicker'),
            Tooltip = require('blocks/tooltip/tooltip');

        require('jquery.maskedinput');

        return Block.extend({
            __name__: 'inputDate',
            className: 'inputDate',
            tagName: 'input',
            date: null,
            noTime: false,
            templates: {
                datepicker__controls: require('ejs!blocks/inputDate/templates/datepicker__controls.html')
            },
            el: '.inputDate',
            initialize: function() {

                var block = this,
                    date = +block.$el.val() || block.$el.val();

                if (block.noTime){
                    block.dateFormat = 'DD.MM.YYYY';
                } else {
                    block.dateFormat = 'DD.MM.YYYY HH:mm'
                }

                block.tooltip = new Tooltip({
                    trigger: block.el
                });

                block.datepicker = new Datepicker({
                    templates: {
                        controls: block.templates.datepicker__controls
                    },
                    noTime: block.noTime
                });

                block.tooltip.el.classList.add('inputDate__tooltip');
                block.datepicker.$el.attr({rel: block.$el.attr('name')});
                $(block.tooltip.el).html(block.datepicker.$el);

                if (date){
                    block.set('date', date);
                }

                if (block.noTime){
                    block.$el.mask('99.99.9999');
                } else {
                    block.$el.mask('99.99.9999 99:99');
                }

                block.listenTo(block.datepicker, {
                    'change:selectedDate': function(date){
                        block.set('date', date, {
                            updateDatepicker: false
                        });
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

                block.tooltip.show();
            },
            remove: function(){
                var block = this;

                block.tooltip.remove();

                Block.prototype.remove.call(block);
            }
        });
    }
);