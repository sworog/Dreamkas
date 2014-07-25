define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page/page');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'stockMovement',
        collections: {
        },
        blocks: {
            inputDate: function() {
                var page = this;

                $('.datepicker-default').datepicker({
                    language: 'ru',
                    format: 'dd.mm.yyyy',
                    autoclose: true
                });

                return {
                    remove: function() {
                        $('.datepicker-default').datepicker('remove')
                    }
                }
            }
        }
    });
});