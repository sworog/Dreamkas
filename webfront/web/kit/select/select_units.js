define(
    [
        './select.js',
        'tpl!./select_units.html'
    ],
    function(Select, indexTemplate) {
        return Select.extend({
            templates: {
                index: indexTemplate
            }
        });
    }
);