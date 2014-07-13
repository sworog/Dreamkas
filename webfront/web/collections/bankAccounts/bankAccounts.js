define(function(require, exports, module) {
    //requirements
    var Collection = require('kit/collection');

    return Collection.extend({
        organizationId: null,
        supplierId: null,
        model: require('models/bankAccount/bankAccount'),
        url: function() {
            var url;

            if (this.organizationId) {
                url = Collection.baseApiUrl + '/organizations/' + this.organizationId + '/bankAccounts';
            }

            if (this.supplierId) {
                url = Collection.baseApiUrl + '/suppliers/' + this.supplierId + '/bankAccounts';
            }

            return url;
        }
    });
});