var expect = require('chai').expect;

module.exports = function() {

    this.Given(/^Я захожу на сайт$/, function(callback) {

        this.browser
            .url(process.env.host + '/test.html')
            .execute(function(){
                require([
                    'app',
                    'resources/currentUser/mocks/get',
                    'resources/store/mocks/get_3'
                ], function(app){
                    app.start('/stores');
                });
            })
            .call(callback);
    });

    this.Then(/^Я вижу заголовок страницы: "([^"]*)"$/, function(arg1, callback) {

        this.browser
            .waitFor('body[status="loaded"]', 5000)
            .getTitle(function(err, title){
                expect(title).to.equal(arg1);
                callback();
            })

    });
};
