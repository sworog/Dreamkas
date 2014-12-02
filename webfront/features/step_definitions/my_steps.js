var webdriverjs = require('webdriverio'),
    expect = require('chai').expect;

var myStepDefinitionsWrapper = function() {

    var browser = webdriverjs.remote({
        desiredCapabilities: {
            browserName: 'chrome'
        }
    });

    var tmpResult = null;

    browser.init();


    this.When(/^Я вызываю getTitle\(\)$/, function(next) {
        browser
            .getTitle(function(err, title) {
                tmpResult = title;
                next();
            });
    });

    this.Then(/^Команда должна вернуть "([^"]*)"$/, function(title, next) {
        expect(tmpResult).to.equal(title);
        next();
    });
};
module.exports = myStepDefinitionsWrapper;