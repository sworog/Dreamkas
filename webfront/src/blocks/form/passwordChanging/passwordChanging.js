define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        model: require('resources/passwordChanging/model'),
        events: {
            'keyup input': function() {
                var block = this;

                block.checkChange();
            }
        },
        submitSuccess: function() {
            var block = this;

            block.$el.find('input').val('');

            return Form.prototype.submitSuccess.apply(block, arguments);
        },
        checkChange: function() {
            var block = this,
                password = this.$el.find('input[name="password"]').val(),
                newPassword = this.$el.find('input[name="newPassword.first"]').val(),
                repeatedNewPassword = this.$el.find('input[name="newPassword.second"]').val();

            if (password != '' && newPassword != '' && repeatedNewPassword != '') {
                block.enable();
            } else {
                block.disable();
            }
        },
        successMessage: 'Пароль изменен.'
    });
});