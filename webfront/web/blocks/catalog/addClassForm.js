define(
    [
        '/kit/form/form.js',
        '/models/catalogClass.js',
        'tpl!./templates/addClassForm.html'
    ],
    function(Form, CatalogClassModel, addClassFormTemplate) {
        return Form.extend({
            Model: CatalogClassModel,
            templates: {
                index: addClassFormTemplate
            }
        });
    }
);