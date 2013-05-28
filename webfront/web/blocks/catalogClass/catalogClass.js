define(
    [
        '/kit/editor/editor.js',
        './catalogClass.templates.js'
    ],
    function(Editor, templates) {
        return Editor.extend({
            editMode: false,
            className: 'catalogClass',
            templates: templates
        })
    }
);