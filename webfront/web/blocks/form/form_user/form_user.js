define(function(require) {
    //requirements
    var Form = require('kit/form');

    return Form.extend({
        model: require('models/user'),
        template: require('rv!./template.html'),
        redirectUrl: function(){
            return '/users/' + this.get('model.id') || '';
        }
    });
});