define(function(require) {
        //requirements
        var Model = require('kit/core/model');

        return Model.extend({
            modelName: 'catalogCategory',
            urlRoot: LH.baseApiUrl + '/categories',
            nestedData: {
                subCategories: require('collections/catalogSubCategories')
            },
            saveData: function(){
                return {
                    name: this.get('name'),
                    group: this.get('group'),
                    retailMarkupMax: this.get('retailMarkupMax'),
                    retailMarkupMin: this.get('retailMarkupMin'),
                    rounding: this.get('rounding') ? this.get('rounding').name : null
                }
            },
            initialize: function() {
                if (this.collection && this.collection.group) {
                    this.set('group', this.collection.group);
                }
            },
            parse: function(response, options) {
                var data = Model.prototype.parse.apply(this, arguments);

                if (typeof data.group == 'object') {
                    data.group = data.group.id;
                }

                if (typeof data.rounding == 'object') {
                    data.rounding = data.rounding.name;
                }

                return data;
            }
        });
    }
);