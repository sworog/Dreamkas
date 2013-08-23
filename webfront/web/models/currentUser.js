define(function(require) {
    //requirements
    var Model = require('kit/model'),
        UserPermissionsModel = require('models/userPermissions'),
        UserStoresCollection = require('collections/userStores');

    var CurrentUserModel = Model.extend({
        modelName: 'currentUser',
        url: LH.baseApiUrl + '/users/current',
        permissions: new UserPermissionsModel(),
        stores: [],
        fetch: function(){
            var model = this;
            return Model.prototype.fetch.apply(model, arguments).then(function(){
                return $.when(model.fetchPermissions(), model.fetchStores());
            });
        },
        fetchPermissions: function(){
            return this.permissions.fetch();
        },
        fetchStores: function(){
            if (this.get('role') === 'ROLE_STORE_MANAGER'){
                this.stores = new UserStoresCollection([], {
                    userId: this.id
                });

                return this.stores.fetch();
            }

            return {};
        }
    });

    return new CurrentUserModel();
});