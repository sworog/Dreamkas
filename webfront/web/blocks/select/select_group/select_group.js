define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block');

    return Block.extend({
        el: '.select_group',
        initialize: function(){
            var block = this;

            Block.prototype.initialize.apply(block, arguments);

            block.$('select').select2({
                minimumInputLength: 1,
                matcher: function (term, text, option) {
                    if (option.attr('add-option') !== undefined && term != '') {
                        return true;
                    }

                    return text.toUpperCase().indexOf(term.toUpperCase())>=0;
                },
                formatResult: function(item, container, query) {
                    item.newGroupName = '';
                    if ($(item.element[0]).attr('add-option') !== undefined) {
                        item.newGroupName = query.term;
                    }
                    return item.text + item.newGroupName;
                },
                formatSelection: function(item, container) {
                    block.$('[name="newGroupName"]').val(item.newGroupName || '');
                    return item.text + (item.newGroupName || '');
                }
            });
        },
        remove: function() {
            var block = this;

            block.$('select').select2('destroy');

            Block.prototype.remove.apply(block, arguments);
        }
    });
});