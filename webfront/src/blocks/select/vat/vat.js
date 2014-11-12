define(function(require, exports, module) {
    //requirements
    var Select = require('blocks/select/select');

    return Select.extend({
        template: require('ejs!./template.ejs'),
        selected: '0',
        vats: {
            '0': 'Не облагается',
            '10': '10%',
            '18': '18%'
        }
    });
});