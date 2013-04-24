define(
    [
        './select.js',
        'tpl!./select_vat.html'
    ],
    function(Select, mainTpl) {
        return Select.extend({
            tpl: {
                main: mainTpl
            }
        });
    }
);