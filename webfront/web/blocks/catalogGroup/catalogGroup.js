define(
    [
        '/kit/editor/editor.js',
        './catalogGroup.templates.js'
    ],
    function(Editor, templates) {
        return Editor.extend({
            className: 'catalogGroup',
            templates: templates
        })
    }
);