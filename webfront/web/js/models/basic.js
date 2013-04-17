var BasicModel = Backbone.Model.extend({
    url: function() {
        if(this.id){
            var url = Backbone.Model.prototype.url.call(this);
            return url + ".json";
        } else {
            return this.urlRoot + ".json";
        }
    },

    sync: function() {
        arguments[2].toSave = true;
        return Backbone.sync.apply(this, arguments);
    },

    toJSON: function(options) {
        _.defaults(options || (options = {}), {
            toSave: false
        });

        if(options.toSave){
            var data = {};
            data[this.modelName] = _.clone(this.attributes);
            delete data[this.modelName].id;

            // Исключаем поля из данных посылаемых на сервер
            if(this.excludeSaveFields != undefined) {
                for(var excludeFieldId in this.excludeSaveFields) {
                    var excludeFieldName = this.excludeSaveFields[excludeFieldId];
                    data[this.modelName][excludeFieldName] = undefined;
                }
            }

            return data;
        }
        else {
            return Backbone.Model.prototype.toJSON.call(this, options);
        }
    },

    parseErrors: function(json) {
        this.errors = {};
        var errorIsset = false;
        for(var field in json.children) {
            if(json.children[field].errors != undefined){
                this.errors[field] = json.children[field].errors;
                errorIsset = true;
            }
        }

        if(errorIsset) {
            this.trigger('error');
        }

        return this;
    },

    errors: {

    }
});