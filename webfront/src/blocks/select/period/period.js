define(function(require, exports, module) {
    //requirements
    var Select = require('blocks/select/select');

    return Select.extend({
        template: require('ejs!./template.ejs'),
        selected: '1',
        items: {
            '1': 'Сегодня',
            '2': 'Вчера',
            '3': 'За неделю'
        }
    });
});