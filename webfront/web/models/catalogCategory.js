define(function(require) {
        //requirements
        var Model = require('kit/model');

        return Model.extend({
            modelName: 'catalogCategory',
            urlRoot: LH.baseApiUrl + '/categories',
            initData: {
                subCategories: require('collections/catalogSubCategories')
            },
            saveFields: [
                'name',
                'group',
                'retailMarkupMax',
                'retailMarkupMin'
            ],
            initialize: function(attrs, options) {

                Model.prototype.initialize.apply(this, arguments);

                if (this.collection && this.collection.group) {
                    this.set('group', this.collection.group);
                }
            },
            parse: function(response, options) {
                var data = Model.prototype.parse.apply(this, arguments);

                if (typeof data.group == 'object') {
                    data.group = data.group.id;
                }

                return data;
            }
        });
    }
);