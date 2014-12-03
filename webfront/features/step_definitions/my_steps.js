var expect = require('chai').expect;

module.exports = function() {

    this.Given(/^Я захожу на сайт$/, function(callback) {

        this.browser
            .url('http://lighthouse.dev')
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
