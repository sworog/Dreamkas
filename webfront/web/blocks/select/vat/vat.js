define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block');

    return Block.extend({
        template: require('ejs!./template.ejs'),
        selected: '0',
        vats: {
            '0': 'Не облагается',
            '10': '10%',
            '18': '18%'
        }
    });
});