define(function(require) {
        //requirements
        var Collection = require('kit/collection');

        return Collection.extend({
            model: require('models/category'),
            group: null,
            store: null,
            initialize: function(data, opt){
                this.group = opt.group;
            },
            url: function() {
                if (this.store) {
                    return Collection.baseApiUrl + '/stores/' + this.store.id + '/groups/' + this.group.id + '/categories'
                } else {
                    return Collection.baseApiUrl + '/groups/' + this.group.id + '/categories'
                }
            }
        });
    }
);