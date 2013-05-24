define(
    [
        './select.js',
        'tpl!./templates/select_vat.html'
    ],
    function(Select, indexTemplate) {
        return Select.extend({
            templates: {
                index: indexTemplate
            }
        });
    }
);