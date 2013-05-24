define(
    [
        '/kit/select/select.js',
        'tpl!./templates/select_units.html'
    ],
    function(Select, indexTemplate) {
        return Select.extend({
            templates: {
                index: indexTemplate
            }
        });
    }
);