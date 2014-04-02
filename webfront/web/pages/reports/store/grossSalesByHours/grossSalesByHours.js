define(function(require, exports, module) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        StoreGrossSalesByHourModel = require('models/storeGrossSalesByHours'),
        currentUserModel = require('models/currentUser');

    require('jquery');

    return Page.extend({
        __name__: module.id,
        storeId: null,
        partials: {
            '#content': require('tpl!./content.html')
        },
        permissions: function() {
            return !LH.isReportsAllow(['storeGrossSalesByHours']);
        },
        models: {
            storeGrossSalesByHours: function() {
                var page = this;

                var storeGrossSalesByHoursModel = new StoreGrossSalesByHourModel();

                storeGrossSalesByHoursModel.storeId = page.storeId;

                return storeGrossSalesByHoursModel;
            },
            store: currentUserModel.stores.length ? currentUserModel.stores.at(0) : null
        },
        initialize: function() {
            var page = this;

            page.models = _.transform(page.models, function(result, model, modelName) {
                result[modelName] = typeof model === 'function' ? model.call(page) : model
            });

            $.when(page.models.storeGrossSalesByHours.fetch()).done(function() {
                page.render();
            });
        }
    });
});