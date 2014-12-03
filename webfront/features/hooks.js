module.exports = function () {

    this.Before(function(scenario, callback) {

        console.log("##teamcity[testSuiteStarted name='suiteName']");
        this.browser.init(callback);
    });

    this.After(function(scenario, callback) {

        console.log("##teamcity[testSuiteFinished name='suiteName']");
        this.browser.end(callback);
    });
};