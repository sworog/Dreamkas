define(
    [
        './select.js',
        'tpl!./select_units.html'
    ],
    function(Select, mainTpl) {
        return Select.extend({
            tpl: {
                main: mainTpl
            }
        });
    }
);