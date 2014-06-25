define(function(require, exports, module) {
    //requirements
    var Form = require('./form'),
        template = '<form>' +
            '<input name="textInput" value="textInputValue" type="text" />' +
            '<input name="checkboxInputChecked" checked type="checkbox" />' +
            '<input name="checkboxInputUnchecked" type="checkbox" />' +
            '<input name="radioInput" value="radioInputChecked" checked type="radio" />' +
            '<input name="radioInput" value="radioInputUnchecked" type="radio" />' +
            '<select name="select">' +
            '<option value="selectedValue" selected></option>' +
            '<option value="unselectedValue"></option>' +
            '</select>' +
            '</form>';

    describe(module.id, function() {
        it('getData method', function() {

            var expectedData = {
                textInput: 'textInputValue',
                checkboxInputChecked: 'on',
                radioInput: 'radioInputChecked',
                select: 'selectedValue'
            };

            var form = new Form({
                template: function() {
                    return template;
                }
            });

            expect(form.getData()).toEqual(expectedData);
        });
    });
});