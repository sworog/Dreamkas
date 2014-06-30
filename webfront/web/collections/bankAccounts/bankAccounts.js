define(function(require, exports, module) {
    //requirements
    var Collection = require('kit/collection');

    return Collection.extend({
        organizationId: null,
        model: require('models/bankAccount/bankAccount'),
        url: function(){
            return Collection.baseApiUrl + '/organizations/' + this.organizationId + '/bankAccounts';
        }
    });
});