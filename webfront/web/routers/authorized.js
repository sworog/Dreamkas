define(function(require) {
    //requirements
    var BaseRouter = require('routers/baseRouter'),
        _ = require('underscore'),
        currentUserModel = require('models/currentUser'),
        LH = require('LH');

    require('routers/writeOff');
    require('routers/catalog');

    var routes = {
        '': require('pages/common/dashboard'),
        '/': require('pages/common/dashboard'),
        'dashboard(/)': require('pages/common/dashboard'),
        'sale(/)': require('pages/common/sale'),
        'users/:userId(/)': function(userId){
            var UserPage = require('pages/user/view'),
                UserForm = require('pages/user/form'),
                Page404 = require('pages/common/404');

            if (currentUserModel.get('id') === userId){
                new UserPage(userId);
            } else if (LH.isAllow('users')){
                userId === 'create' ? new UserForm() : new UserPage(userId);
            } else {
                new Page404();
            }
        }
    };

    if (LH.isAllow('balance')) {
        _.extend(routes, {
            'balance(/)': require('pages/common/balance')
        });
    }

    if (LH.isAllow('invoices')) {
        _.extend(routes, {
            'invoices(/)': require('pages/invoice/list'),
            'invoices/create(/)': require('pages/invoice/form'),
            'invoices/:invoiceId(/)': require('pages/invoice/view')
        });
    }

    if (LH.isAllow('users')) {
        _.extend(routes, {
            'users(/)': require('pages/user/list'),
            'users/edit/:userId(/)': require('pages/user/form')
        });
    }

    if (LH.isAllow('products')) {
        _.extend(routes, {
            'products(/)': require('pages/product/list'),
            'products/edit/:productId(/)': require('pages/product/form'),
            'products/create(/)': require('pages/product/form'),
            'products/:productId(/)': require('pages/product/view')
        });
    }

    var Router = BaseRouter.extend({
        routes: _.extend(routes, {
            '*path': require('pages/common/404')
        })
    });

    return new Router();
});
