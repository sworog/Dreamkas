define(function(require, exports, module) {
    //requirements
    var Table = require('blocks/table/table');

    return Table.extend({
        template: require('ejs!./template.ejs'),
        events: {
            'change select[name="period"]': function(e) {
                var block = this,
                    select = e.currentTarget;

            }
        },
        blocks: {
            select_period: require('blocks/select/period/period')
        }
    });
});