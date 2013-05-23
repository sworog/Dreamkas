define(
    [
        './select.js',
        'tpl!./select_vat.html'
    ],
    function(Select, indexTemplate) {
        return Select.extend({
            templates: {
                index: indexTemplate
            }
        });
    }
);